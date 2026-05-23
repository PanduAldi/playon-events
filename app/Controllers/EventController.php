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

    public function processCheckout($slug)
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
            'full_name' => 'required|min_length[3]',
            'phone' => 'required|numeric',
            'email' => 'required|valid_email',
            'birth_date' => 'required|valid_date',
            'gender' => 'required|in_list[M,F]',
            'emergency_contact' => 'required',
            'recaptcha_token' => 'required'
        ];

        $recaptchaToken = $this->request->getPost('recaptcha_token');
        $recaptchaValid = $this->verifyRecaptchaToken($recaptchaToken);
        if (!$recaptchaValid['success']) {
            return redirect()->back()->withInput()->with('error', $recaptchaValid['message']);
        }

        $ip = $this->request->getIPAddress();
        $cache = \Config\Services::cache();
        $cacheKey = 'checkout_attempt_' . md5($ip);
        $attempts = (int) $cache->get($cacheKey);

        if ($attempts >= 10) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan pendaftaran. Silakan coba lagi setelah beberapa menit.');
        }

        if (!$this->validate($rules)) {
            $cache->save($cacheKey, $attempts + 1, 900);
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $categoryId = $this->request->getPost('category_id');
        
        $db->transBegin();

        try {
            // Lock row untuk kategori yang dipilih
            $categorySql = $db->table('event_categories')
                              ->where('id', $categoryId)
                              ->where('event_id', $event['id'])
                              ->getCompiledSelect() . ' FOR UPDATE';

            $category = $db->query($categorySql)->getRowArray();

            if (!$category) {
                throw new \Exception('Kategori tidak valid.');
            }

            if ($category['registered_count'] >= $category['max_participants']) {
                throw new \Exception('Maaf, kuota untuk kategori ini sudah penuh.');
            }

            // Simpan Data Participant
            $participantData = [
                'full_name' => $this->request->getPost('full_name'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
                'birth_date' => $this->request->getPost('birth_date'),
                'gender' => $this->request->getPost('gender'),
                'shirt_size' => $this->request->getPost('shirt_size') ?: null,
                'club_name' => $this->request->getPost('club_name') ?: null,
                'emergency_contact' => $this->request->getPost('emergency_contact'),
                'medical_notes' => $this->request->getPost('medical_notes') ?: null,
            ];

            // Cek jika email sudah ada (bisa diupdate atau insert baru, asumsi sederhana insert baru jika tidak mau ribet)
            // Namun sebaiknya dicari dulu berdasarkan email.
            $existingParticipant = $participantModel->where('email', $participantData['email'])->first();
            if ($existingParticipant) {
                $participantId = $existingParticipant['id'];
                $participantModel->update($participantId, $participantData);
            } else {
                $participantId = $participantModel->insert($participantData);
            }

            // Generate BIB
            $bibNumber = 'BIB-' . strtoupper(substr($event['slug'], 0, 3)) . '-' . $category['code'] . '-' . str_pad($category['registered_count'] + 1, 4, '0', STR_PAD_LEFT);
            $qrToken = hash('sha256', $participantId . '-' . $event['id'] . '-' . time());
            $paymentStatus = ($event['event_type'] == 'free' || $category['fee'] == 0) ? 'free' : 'unpaid';

            // Insert Registration
            $regData = [
                'participant_id' => $participantId,
                'event_id' => $event['id'],
                'category_id' => $categoryId,
                'bib_number' => $bibNumber,
                'qr_token' => $qrToken,
                'payment_status' => $paymentStatus,
                'status' => 'pending', // or confirmed for free
                'registered_at' => date('Y-m-d H:i:s')
            ];

            if ($paymentStatus == 'free') {
                $regData['status'] = 'confirmed';
            }

            // Check for duplicate registration
            $existingReg = $registrationModel->where([
                'participant_id' => $participantId,
                'event_id' => $event['id'],
                'category_id' => $categoryId
            ])->first();

            if ($existingReg) {
                throw new \Exception('Anda sudah terdaftar di event dan kategori ini.');
            }

            $registrationId = $registrationModel->insert($regData);

            // Increment registered_count
            $db->table('event_categories')
               ->where('id', $categoryId)
               ->set('registered_count', 'registered_count + 1', false)
               ->update();

            $db->transCommit();
            $cache->delete($cacheKey);

            $emailStatus = '';
            try {
                $categoryName = $category['name'];
                $paymentLabel = $paymentStatus === 'free' ? 'Gratis / Terbayar otomatis' : 'Belum dibayar';

                $emailBody = "<p>Halo " . htmlspecialchars($participantData['full_name'], ENT_QUOTES, 'UTF-8') . ",</p>";
                $emailBody .= "<p>Terima kasih telah mendaftar pada event <strong>" . htmlspecialchars($event['name'], ENT_QUOTES, 'UTF-8') . "</strong> dalam kategori <strong>" . htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') . "</strong>.</p>";
                $emailBody .= "<ul>";
                $emailBody .= "<li><strong>Nomor BIB:</strong> " . htmlspecialchars($bibNumber, ENT_QUOTES, 'UTF-8') . "</li>";
                $emailBody .= "<li><strong>Status Pembayaran:</strong> " . htmlspecialchars($paymentLabel, ENT_QUOTES, 'UTF-8') . "</li>";
                $emailBody .= "</ul>";

                if ($paymentStatus !== 'free') {
                    $emailBody .= "<div style='padding: 16px; background-color: #fff4e5; border: 1px solid #ffddb2; border-radius: 8px; margin-top: 16px;'>";
                    $emailBody .= "<p style='margin: 0 0 8px;'>Silakan melakukan pembayaran ke rekening berikut:</p>";
                    $emailBody .= "<p style='margin: 0; font-weight: 600;'>Bank BCA - 1234567890 (a.n. Playon Brebes)</p>";
                    $emailBody .= "<p style='margin: 16px 0 0 0;'>Setelah transfer, konfirmasi melalui WhatsApp Admin di <strong>0811-2222-3333</strong>.</p>";
                    $emailBody .= "</div>";
                }

                $emailBody .= "<p style='margin-top: 24px;'>Semoga sukses dan sampai jumpa di event!</p>";

                $emailResult = $this->sendMail(
                    $participantData['email'], 
                    'Pendaftaran Berhasil - ' . $event['name'], 
                    $emailBody,
                    true
                );

                if ($emailResult['success']) {
                    session()->setFlashdata('email_status', 'Email konfirmasi telah dikirim ke ' . $participantData['email'] . '.');
                } else {
                    session()->setFlashdata('email_status', 'Pendaftaran berhasil, tetapi email konfirmasi gagal dikirim. ' . $emailResult['error']);
                }
            } catch (\Exception $e) {
                session()->setFlashdata('email_status', 'Pendaftaran berhasil, tetapi email konfirmasi gagal dikirim: ' . $e->getMessage());
            }

            return redirect()->to('/event/' . $slug . '/success?reg=' . $registrationId);

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    protected function verifyRecaptchaToken(?string $token): array
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
        if ($action !== 'checkout' || $score < 0.4) {
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
