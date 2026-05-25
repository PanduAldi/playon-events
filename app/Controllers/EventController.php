<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\CategoryModel;
use App\Models\ParticipantModel;
use App\Models\RegistrationModel;

class EventController extends BaseController
{
    public function detail($slug)
    {
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();
        $registrationModel = new RegistrationModel();

        $event = $eventModel->getEventBySlug($slug);
        if (!$event || $event['status'] !== 'active') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event tidak ditemukan atau tidak aktif.');
        }

        $categories = $categoryModel->getCategoriesByEvent($event['id']);

        // Hitung total peserta terdaftar untuk seluruh event ini (exclude cancelled)
        $totalRegistered = $registrationModel->where('event_id', $event['id'])
                                            ->where('status !=', 'cancelled')
                                            ->countAllResults();

        $data = [
            'event' => $event,
            'categories' => $categories,
            'total_registered' => $totalRegistered
        ];

        return view('public/event_detail', $data);
    }

    public function checkout($slug)
    {
        //pldes
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();
        $registrationModel = new RegistrationModel();

        $event = $eventModel->getEventBySlug($slug);
        if (!$event || $event['status'] !== 'active') {
            return redirect()->to('/')->with('error', 'Event tidak valid.');
        }

        $categories = $categoryModel->getCategoriesByEvent($event['id']);
        
        // Filter out categories that are full
        $availableCategories = [];
        foreach($categories as $cat) {
            if ($cat['registered_count'] < $cat['max_participants']) {
                $availableCategories[] = $cat;
            }
        }

        $data = [
            'event' => $event,
            'categories' => $availableCategories,
            'recaptchaSiteKey' => getenv('recaptcha.siteKey') ?: ''
        ];

        return view('public/checkout', $data);
    }

    public function communityRegister($slug)
    {
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();

        $event = $eventModel->getEventBySlug($slug);
        if (!$event || $event['status'] !== 'active') {
            return redirect()->to('/')->with('error', 'Event tidak valid atau tidak aktif.');
        }

        $categories = $categoryModel->getCategoriesByEvent($event['id']);
        $availableCategories = [];
        foreach ($categories as $cat) {
            if ($cat['registered_count'] < $cat['max_participants']) {
                $availableCategories[] = $cat;
            }
        }

        if (empty($availableCategories)) {
            return redirect()->to('/')->with('error', 'Semua kategori sudah penuh.');
        }

        return view('public/community_checkout', [
            'event' => $event,
            'categories' => $availableCategories,
            'recaptchaSiteKey' => getenv('recaptcha.siteKey') ?: ''
        ]);
    }

    public function processCommunityRegistration($slug)
    {
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();
        $participantModel = new ParticipantModel();
        $registrationModel = new RegistrationModel();
        $db = \Config\Database::connect();

        $event = $eventModel->getEventBySlug($slug);
        if (!$event) {
            return redirect()->to('/')->with('error', 'Event tidak ditemukan.');
        }

        $rules = [
            'category_id' => 'required|numeric',
            'email' => 'required|valid_email',
            'phone' => 'required|numeric',
            'club_name' => 'required|min_length[3]',
            'recaptcha_token' => 'required'
        ];

        $recaptchaToken = $this->request->getPost('recaptcha_token');
        $recaptchaValid = $this->verifyRecaptchaToken($recaptchaToken, 'community');
        if (!$recaptchaValid['success']) {
            return redirect()->back()->withInput()->with('error', $recaptchaValid['message']);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $runners = $this->request->getPost('runners');
        if (!is_array($runners) || empty($runners)) {
            return redirect()->back()->withInput()->with('error', 'Masukkan minimal satu pelari untuk pendaftaran komunitas.');
        }

        $runnerData = [];
        foreach ($runners as $index => $runner) {
            $fullName = trim($runner['full_name'] ?? '');
            $gender = $runner['gender'] ?? '';
            $birthDate = trim($runner['birth_date'] ?? '');

            if ($fullName === '' && $gender === '' && $birthDate === '') {
                continue;
            }

            if ($fullName === '') {
                return redirect()->back()->withInput()->with('error', 'Nama pelari pada baris ' . ($index + 1) . ' tidak boleh kosong.');
            }

            if (!in_array($gender, ['M', 'F'], true)) {
                return redirect()->back()->withInput()->with('error', 'Jenis kelamin pelari pada baris ' . ($index + 1) . ' tidak valid.');
            }

            if ($birthDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate)) {
                return redirect()->back()->withInput()->with('error', 'Tanggal lahir pelari pada baris ' . ($index + 1) . ' harus dalam format YYYY-MM-DD.');
            }

            if ($birthDate === '') {
                $birthDate = date('Y-m-d', strtotime('-16 years'));
            }

            $runnerData[] = [
                'full_name' => $fullName,
                'gender' => $gender,
                'birth_date' => $birthDate,
            ];
        }

        if (empty($runnerData)) {
            return redirect()->back()->withInput()->with('error', 'Masukkan minimal satu pelari untuk pendaftaran komunitas.');
        }

        $categoryId = $this->request->getPost('category_id');

        $db->transBegin();

        try {
            $categorySql = $db->table('event_categories')
                              ->where('id', $categoryId)
                              ->where('event_id', $event['id'])
                              ->getCompiledSelect() . ' FOR UPDATE';

            $category = $db->query($categorySql)->getRowArray();

            if (!$category) {
                throw new \Exception('Kategori tidak valid.');
            }

            $requiredSeats = count($runnerData);
            if ($category['registered_count'] + $requiredSeats > $category['max_participants']) {
                throw new \Exception('Maaf, kuota tidak mencukupi untuk jumlah pelari komunitas yang dimasukkan.');
            }

            $savedRegistrations = [];
            $paymentStatus = ($event['event_type'] === 'free' || $category['fee'] == 0) ? 'free' : 'unpaid';

            foreach ($runnerData as $runnerIndex => $runner) {
                $participantData = [
                    'full_name' => $runner['full_name'],
                    'phone' => $this->request->getPost('phone'),
                    'email' => $this->request->getPost('email'),
                    'birth_date' => $runner['birth_date'],
                    'gender' => $runner['gender'],
                    'shirt_size' => null,
                    'club_name' => $this->request->getPost('club_name') ?: null,
                    'medical_notes' => null,
                ];

                $participantId = $participantModel->insert($participantData);

                $bibNumber = 'BIB-' . strtoupper(substr($event['slug'], 0, 3)) . '-' . $category['code'] . '-' . str_pad($category['registered_count'] + $runnerIndex + 1, 4, '0', STR_PAD_LEFT);
                $qrToken = hash('sha256', $participantId . '-' . $event['id'] . '-' . time() . '-' . $runnerIndex);

                $registrationId = $registrationModel->insert([
                    'participant_id' => $participantId,
                    'event_id' => $event['id'],
                    'category_id' => $categoryId,
                    'bib_number' => $bibNumber,
                    'qr_token' => $qrToken,
                    'payment_status' => $paymentStatus,
                    'status' => $paymentStatus === 'free' ? 'confirmed' : 'pending',
                    'registered_at' => date('Y-m-d H:i:s')
                ]);

                $savedRegistrations[] = [
                    'bib' => $bibNumber,
                    'name' => $runner['full_name']
                ];
            }

            $db->table('event_categories')
               ->where('id', $categoryId)
               ->set('registered_count', 'registered_count + ' . $requiredSeats, false)
               ->update();

            $db->transCommit();

            $emailBody = '<p>Halo Koordinator,</p>';
            $emailBody .= '<p>Pendaftaran komunitas Anda untuk event <strong>' . htmlspecialchars($event['name'], ENT_QUOTES, 'UTF-8') . '</strong> berhasil diproses.</p>';
            $emailBody .= '<p>Detail kategori: <strong>' . htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') . '</strong></p>';
            $emailBody .= '<p>Jumlah pelari: <strong>' . count($savedRegistrations) . '</strong></p>';
            $emailBody .= '<ul>';
            foreach ($savedRegistrations as $saved) {
                $emailBody .= '<li>' . htmlspecialchars($saved['name'], ENT_QUOTES, 'UTF-8') . ' - BIB: ' . htmlspecialchars($saved['bib'], ENT_QUOTES, 'UTF-8') . '</li>';
            }
            $emailBody .= '</ul>';

            if ($paymentStatus !== 'free') {
                $emailBody .= '<p>Semua pelari berstatus <strong>Belum dibayar</strong>. Silakan lakukan pembayaran dengan total sesuai jumlah pelari dan fee kategori.</p>';
                $emailBody .= '<p>Rekening tujuan: <strong>Bank BCA - 1234567890 (a.n. Playon Brebes)</strong></p>';
                $emailBody .= '<p>Setelah transfer, konfirmasi melalui WhatsApp Admin di <strong>0811-2222-3333</strong>.</p>';
            }

            $emailBody .= '<p>Terima kasih dan semoga sukses!</p>';

            $emailResult = $this->sendMail(
                $this->request->getPost('email'),
                'Pendaftaran Komunitas Berhasil - ' . $event['name'], 
                $emailBody,
                true
            );

            if ($emailResult['success']) {
                session()->setFlashdata('email_status', 'Link konfirmasi telah dikirim ke ' . $this->request->getPost('email') . '.');
            } else {
                session()->setFlashdata('email_status', 'Pendaftaran berhasil, tetapi email konfirmasi gagal dikirim. ' . $emailResult['error']);
            }

            return redirect()->to('/event/' . $slug . '/community/success?count=' . count($savedRegistrations) . '&category=' . $categoryId);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function communitySuccess($slug)
    {
        $count = (int) $this->request->getGet('count');
        $categoryId = (int) $this->request->getGet('category');

        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();

        $event = $eventModel->getEventBySlug($slug);
        if (!$event || $count <= 0) {
            return redirect()->to('/');
        }

        $category = $categoryModel->find($categoryId);
        if (!$category) {
            return redirect()->to('/');
        }

        return view('public/community_success', [
            'event' => $event,
            'category' => $category,
            'count' => $count,
            'email_status' => session()->getFlashdata('email_status')
        ]);
    }

    protected function verifyRecaptchaToken(?string $token, string $expectedAction = 'checkout'): array
    {
        $secret = getenv('recaptcha.secretKey') ?: '';
        if (empty($secret) || empty($token)) {
            return [
                'success' => false,
                'message' => 'Verifikasi reCAPTCHA gagal. Coba refresh halaman dan ulangi.'
            ];
        }

        $endpoint = 'https://www.google.com/recaptcha/api/siteverify';
        $payload = http_build_query([
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $this->request->getIPAddress()
        ]);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                            "User-Agent: Playon Events reCAPTCHA/1.0\r\n",
                'content' => $payload,
                'timeout' => 10,
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($endpoint, false, $context);

        if ($response === false) {
            if (function_exists('curl_init')) {
                $ch = curl_init($endpoint);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/x-www-form-urlencoded',
                    'User-Agent: Playon Events reCAPTCHA/1.0',
                ]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $response = curl_exec($ch);
                curl_close($ch);
            }
        }

        if ($response === false || $response === null) {
            return [
                'success' => false,
                'message' => 'Tidak dapat memverifikasi reCAPTCHA saat ini. Silakan coba lagi.'
            ];
        }

        $data = json_decode($response, true);
        if (!is_array($data) || empty($data['success'])) {
            return [
                'success' => false,
                'message' => 'reCAPTCHA tidak valid. Pastikan Anda bukan bot dan coba lagi.'
            ];
        }

        $score = $data['score'] ?? 0;
        $action = $data['action'] ?? '';
        if ($action !== $expectedAction || $score < 0.4) {
            return [
                'success' => false,
                'message' => 'reCAPTCHA tidak cukup kuat. Silakan coba lagi.'
            ];
        }

        return [
            'success' => true,
            'message' => ''
        ];
    }

    public function success($slug)
    {
        $regId = $this->request->getGet('reg');
        if (!$regId) return redirect()->to('/');

        $registrationModel = new RegistrationModel();
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();
        $participantModel = new ParticipantModel();

        $registration = $registrationModel->find($regId);
        if (!$registration) return redirect()->to('/');

        $event = $eventModel->find($registration['event_id']);
        $category = $categoryModel->find($registration['category_id']);
        $participant = $participantModel->find($registration['participant_id']);

        $data = [
            'event' => $event,
            'category' => $category,
            'participant' => $participant,
            'registration' => $registration,
            'email_status' => session()->getFlashdata('email_status')
        ];

        return view('public/success', $data);
    }
}
