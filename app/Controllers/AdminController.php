<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\CategoryModel;
use App\Models\ParticipantModel;
use App\Models\RegistrationModel;

class AdminController extends BaseController
{
    public function login()
    {
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    public function processLogin()
    {
        $password = $this->request->getPost('password');
        // MVP: Hardcoded password "admin123"
        if ($password === 'admin123') {
            session()->set('is_admin_logged_in', true);
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->back()->with('error', 'Password salah.');
    }

    public function logout()
    {
        session()->remove('is_admin_logged_in');
        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $eventModel = new EventModel();
        $registrationModel = new RegistrationModel();
        $db = \Config\Database::connect();

        $totalEvents = $eventModel->where('status', 'active')->countAllResults();
        $totalParticipants = $registrationModel->countAllResults();
        $paidParticipants = $registrationModel->where('payment_status', 'paid')->countAllResults();
        $checkedInParticipants = $registrationModel->where('attended_at IS NOT NULL')->countAllResults();

        $events = $eventModel->whereIn('status', ['active', 'closed'])->orderBy('event_date', 'ASC')->findAll();
        $categoryModel = new CategoryModel();
        foreach ($events as &$event) {
            $event['categories'] = $categoryModel->getCategoriesByEvent($event['id']);
            $event['registered_count'] = array_sum(array_column($event['categories'], 'registered_count'));
            $event['max_participants'] = array_sum(array_column($event['categories'], 'max_participants'));
        }

        $recentRegistrations = $db->table('registrations r')
            ->select('r.bib_number, r.payment_status, r.status, r.registered_at, p.full_name, e.name as event_name, c.name as category_name')
            ->join('participants p', 'p.id = r.participant_id')
            ->join('events e', 'e.id = r.event_id')
            ->join('event_categories c', 'c.id = r.category_id')
            ->orderBy('r.registered_at', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();
        
        $data = [
            'total_events' => $totalEvents,
            'total_participants' => $totalParticipants,
            'paid_participants' => $paidParticipants,
            'checked_in_participants' => $checkedInParticipants,
            'events' => $events,
            'recent_registrations' => $recentRegistrations
        ];

        return view('admin/dashboard', $data);
    }

    public function participants()
    {
        $registrations = $this->getRegistrationRows();
        $eventModel = new EventModel();

        $data = [
            'registrations' => $registrations,
            'events' => $eventModel->orderBy('event_date', 'DESC')->findAll(),
            'filters' => [
                'event_id' => $this->request->getGet('event_id'),
                'payment_status' => $this->request->getGet('payment_status'),
                'status' => $this->request->getGet('status'),
                'q' => $this->request->getGet('q'),
            ]
        ];

        return view('admin/participants', $data);
    }

    public function viewParticipant($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('registrations r');
        $builder->select('r.id, r.bib_number, r.payment_status, r.status, r.registration_type, r.registered_at, r.attended_at, r.qr_token, p.full_name, p.email, p.phone, p.birth_date, p.gender, p.shirt_size, p.club_name, p.emergency_contact, p.medical_notes, e.name as event_name, e.event_date, e.location, e.event_type, c.name as category_name, c.fee');
        $builder->join('participants p', 'p.id = r.participant_id');
        $builder->join('events e', 'e.id = r.event_id');
        $builder->join('event_categories c', 'c.id = r.category_id');
        $builder->where('r.id', $id);

        $registration = $builder->get()->getRowArray();

        if (!$registration) {
            return redirect()->to('/admin/participants')->with('error', 'Peserta tidak ditemukan.');
        }

        return view('admin/participant_detail', ['registration' => $registration]);
    }

    public function updateStatus()
    {
        $regId = $this->request->getPost('reg_id');
        $paymentStatus = $this->request->getPost('payment_status');
        $participantStatus = $this->request->getPost('status');
        
        $registrationModel = new RegistrationModel();
        
        if ($regId) {
            $payload = [];

            if (in_array($paymentStatus, ['free', 'unpaid', 'paid'], true)) {
                $payload['payment_status'] = $paymentStatus;
                if ($paymentStatus === 'paid') {
                    $payload['status'] = 'confirmed';
                }
            }

            if (in_array($participantStatus, ['pending', 'confirmed', 'attended', 'cancelled'], true)) {
                $payload['status'] = $participantStatus;
                if ($participantStatus === 'attended') {
                    $payload['attended_at'] = date('Y-m-d H:i:s');
                }
            }

            if (!empty($payload)) {
                $registrationModel->update($regId, $payload);
            }
        }
        
        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    public function exportParticipants()
    {
        $rows = $this->getRegistrationRows();
        $filename = 'playon-peserta-' . date('Ymd-His') . '.csv';

        $this->response->setHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['BIB', 'Nama', 'Email', 'Telepon', 'Event', 'Kategori', 'Biaya', 'Pembayaran', 'Status Peserta', 'Tanggal Daftar']);

        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['bib_number'],
                $row['full_name'],
                $row['email'],
                $row['phone'],
                $row['event_name'],
                $row['category_name'],
                $row['fee'],
                $row['payment_status'],
                $row['status'],
                $row['registered_at'],
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $this->response->setBody($csv);
    }

    public function scanner()
    {
        return view('admin/scanner');
    }

    public function processScan()
    {
        $qrToken = $this->request->getJSON()->qr_token ?? null;

        if (!$qrToken) {
            return $this->response->setJSON([
                'success' => false,
                'type' => 'error',
                'message' => 'Token QR tidak valid atau kosong.'
            ]);
        }

        $db = \Config\Database::connect();

        // Cari registrasi berdasarkan qr_token beserta data relasi
        $builder = $db->table('registrations r');
        $builder->select('r.id as reg_id, r.bib_number, r.payment_status, r.status, r.attended_at, p.full_name, e.name as event_name, c.name as category_name');
        $builder->join('participants p', 'p.id = r.participant_id');
        $builder->join('events e', 'e.id = r.event_id');
        $builder->join('event_categories c', 'c.id = r.category_id');
        $builder->where('r.qr_token', $qrToken);

        $reg = $builder->get()->getRowArray();

        if (!$reg) {
            return $this->response->setJSON([
                'success' => false,
                'type' => 'not_found',
                'message' => 'Tiket tidak ditemukan. QR Code tidak terdaftar di sistem.'
            ]);
        }

        // Cek pembayaran
        if ($reg['payment_status'] === 'unpaid') {
            return $this->response->setJSON([
                'success' => false,
                'type' => 'unpaid',
                'message' => 'Peserta "' . $reg['full_name'] . '" belum melunasi pembayaran.',
                'data' => $reg
            ]);
        }

        // Cek apakah sudah pernah check-in
        if (!empty($reg['attended_at'])) {
            return $this->response->setJSON([
                'success' => false,
                'type' => 'already_checked_in',
                'message' => 'Peserta "' . $reg['full_name'] . '" sudah check-in pada ' . date('d M Y, H:i', strtotime($reg['attended_at'])) . '.',
                'data' => $reg
            ]);
        }

        // Semua validasi lolos, update attended_at
        $registrationModel = new RegistrationModel();
        $registrationModel->update($reg['reg_id'], [
            'status' => 'attended',
            'attended_at' => date('Y-m-d H:i:s')
        ]);

        $reg['attended_at'] = date('Y-m-d H:i:s');

        return $this->response->setJSON([
            'success' => true,
            'type' => 'checked_in',
            'message' => 'Check-in berhasil untuk "' . $reg['full_name'] . '"!',
            'data' => $reg
        ]);
    }

    private function getRegistrationRows(): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('registrations r');
        $builder->select('r.id, r.bib_number, r.payment_status, r.status, r.registration_type, r.registered_at, r.attended_at, p.full_name, p.email, p.phone, e.id as event_id, e.name as event_name, c.name as category_name, c.fee, e.event_type');
        $builder->join('participants p', 'p.id = r.participant_id');
        $builder->join('events e', 'e.id = r.event_id');
        $builder->join('event_categories c', 'c.id = r.category_id');

        $eventId = $this->request->getGet('event_id');
        $paymentStatus = $this->request->getGet('payment_status');
        $status = $this->request->getGet('status');
        $keyword = trim((string) $this->request->getGet('q'));

        if ($eventId) {
            $builder->where('e.id', $eventId);
        }

        if (in_array($paymentStatus, ['free', 'unpaid', 'paid'], true)) {
            $builder->where('r.payment_status', $paymentStatus);
        }

        if (in_array($status, ['pending', 'confirmed', 'attended', 'cancelled'], true)) {
            $builder->where('r.status', $status);
        }

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('p.full_name', $keyword)
                ->orLike('p.email', $keyword)
                ->orLike('p.phone', $keyword)
                ->orLike('r.bib_number', $keyword)
                ->groupEnd();
        }

        return $builder->orderBy('r.registered_at', 'DESC')->get()->getResultArray();
    }

    public function events()
    {
        $eventModel = new EventModel();
        $data = [
            'events' => $eventModel->orderBy('event_date', 'DESC')->findAll()
        ];
        return view('admin/events/index', $data);
    }

    public function newEvent()
    {
        return view('admin/events/create');
    }

    public function createEvent()
    {
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'event_date' => 'required',
            'location' => 'required|max_length[300]',
            'registration_open' => 'required',
            'registration_close' => 'required',
            'status' => 'required|in_list[draft,active,closed,finished]',
            'event_type' => 'required|in_list[free,paid]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        
        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while ($eventModel->where('slug', $slug)->first()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $bannerName = null;
        $bannerFile = $this->request->getFile('banner_image');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $bannerName = $bannerFile->getRandomName();
            if (!is_dir(FCPATH . 'uploads')) {
                mkdir(FCPATH . 'uploads', 0777, true);
            }
            $bannerFile->move(FCPATH . 'uploads', $bannerName);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $eventId = $eventModel->insert([
                'slug' => $slug,
                'name' => $name,
                'description' => $this->request->getPost('description'),
                'event_date' => $this->request->getPost('event_date'),
                'location' => $this->request->getPost('location'),
                'maps_url' => $this->request->getPost('maps_url'),
                'banner_image' => $bannerName,
                'registration_open' => $this->request->getPost('registration_open'),
                'registration_close' => $this->request->getPost('registration_close'),
                'status' => $this->request->getPost('status'),
                'event_type' => $this->request->getPost('event_type'),
                'allow_waitlist' => $this->request->getPost('allow_waitlist') ? 1 : 0,
            ]);

            // Save categories
            $postedCategories = $this->request->getPost('categories');
            if (is_array($postedCategories) && !empty($postedCategories)) {
                foreach ($postedCategories as $postedCat) {
                    if (empty($postedCat['name']) || empty($postedCat['code'])) {
                        continue;
                    }
                    $categoryModel->insert([
                        'event_id' => $eventId,
                        'name' => $postedCat['name'],
                        'code' => strtoupper($postedCat['code']),
                        'fee' => $postedCat['fee'] ?? 0,
                        'max_participants' => $postedCat['max_participants'] ?? 100,
                        'max_individual' => $postedCat['max_individual'] ?? ($postedCat['max_participants'] ?? 100),
                        'max_community' => $postedCat['max_community'] ?? ($postedCat['max_participants'] ?? 100),
                        'registered_count' => 0
                    ]);
                }
            } else {
                // Default category if none provided
                $categoryModel->insert([
                    'event_id' => $eventId,
                    'name' => 'Umum',
                    'code' => 'GEN',
                    'fee' => 0,
                    'max_participants' => 100,
                    'registered_count' => 0
                ]);
            }

            $db->transCommit();
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['db' => $e->getMessage()]);
        }

        return redirect()->to('/admin/events')->with('success', 'Event beserta kategori berhasil ditambahkan.');
    }

    public function editEvent($id)
    {
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();

        $event = $eventModel->find($id);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event tidak ditemukan.');
        }

        $categories = $categoryModel->getCategoriesByEvent($id);

        return view('admin/events/edit', [
            'event' => $event,
            'categories' => $categories
        ]);
    }

    public function updateEvent($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'event_date' => 'required',
            'location' => 'required|max_length[300]',
            'registration_open' => 'required',
            'registration_close' => 'required',
            'status' => 'required|in_list[draft,active,closed,finished]',
            'event_type' => 'required|in_list[free,paid]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        
        // Ensure unique slug (excluding current event)
        $originalSlug = $slug;
        $count = 1;
        while ($existing = $eventModel->where('slug', $slug)->first()) {
            if ($existing['id'] == $id) {
                break;
            }
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $bannerName = $event['banner_image'];
        $bannerFile = $this->request->getFile('banner_image');
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $bannerName = $bannerFile->getRandomName();
            if (!is_dir(FCPATH . 'uploads')) {
                mkdir(FCPATH . 'uploads', 0777, true);
            }
            $bannerFile->move(FCPATH . 'uploads', $bannerName);
            // Optionally delete old banner
            if ($event['banner_image'] && file_exists(FCPATH . 'uploads/' . $event['banner_image'])) {
                @unlink(FCPATH . 'uploads/' . $event['banner_image']);
            }
        }

        $eventModel->update($id, [
            'slug' => $slug,
            'name' => $name,
            'description' => $this->request->getPost('description'),
            'event_date' => $this->request->getPost('event_date'),
            'location' => $this->request->getPost('location'),
            'maps_url' => $this->request->getPost('maps_url'),
            'banner_image' => $bannerName,
            'registration_open' => $this->request->getPost('registration_open'),
            'registration_close' => $this->request->getPost('registration_close'),
            'status' => $this->request->getPost('status'),
            'event_type' => $this->request->getPost('event_type'),
            'allow_waitlist' => $this->request->getPost('allow_waitlist') ? 1 : 0,
        ]);

        return redirect()->to('/admin/events')->with('success', 'Event berhasil diperbarui.');
    }

    public function deleteEvent($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event tidak ditemukan.');
        }

        // Delete banner image if exists
        if ($event['banner_image'] && file_exists(FCPATH . 'uploads/' . $event['banner_image'])) {
            @unlink(FCPATH . 'uploads/' . $event['banner_image']);
        }

        $eventModel->delete($id);

        return redirect()->to('/admin/events')->with('success', 'Event berhasil dihapus.');
    }

    public function createCategory()
    {
        $categoryModel = new CategoryModel();
        $eventId = $this->request->getPost('event_id');

        $rules = [
            'event_id' => 'required|numeric',
            'name' => 'required|min_length[2]|max_length[100]',
            'code' => 'required|min_length[1]|max_length[20]',
            'fee' => 'required|numeric|greater_than_equal_to[0]',
            'max_participants' => 'required|numeric|greater_than[0]',
            'max_individual' => 'required|numeric|greater_than_equal_to[0]',
            'max_community' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/events/edit/' . $eventId)->with('cat_error', 'Input data kategori tidak valid.');
        }

        $categoryModel->insert([
            'event_id' => $eventId,
            'name' => $this->request->getPost('name'),
            'code' => strtoupper($this->request->getPost('code')),
            'fee' => $this->request->getPost('fee'),
            'max_participants' => $this->request->getPost('max_participants'),
            'max_individual' => $this->request->getPost('max_individual'),
            'max_community' => $this->request->getPost('max_community'),
            'registered_count' => 0
        ]);

        return redirect()->to('/admin/events/edit/' . $eventId)->with('cat_success', 'Kategori baru berhasil ditambahkan.');
    }

    public function updateCategory($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);
        if (!$category) {
            return redirect()->back()->with('cat_error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'code' => 'required|min_length[1]|max_length[20]',
            'fee' => 'required|numeric|greater_than_equal_to[0]',
            'max_participants' => 'required|numeric|greater_than[0]',
            'max_individual' => 'required|numeric|greater_than_equal_to[0]',
            'max_community' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/events/edit/' . $category['event_id'])->with('cat_error', 'Input data kategori tidak valid.');
        }

        $categoryModel->update($id, [
            'name' => $this->request->getPost('name'),
            'code' => strtoupper($this->request->getPost('code')),
            'fee' => $this->request->getPost('fee'),
            'max_participants' => $this->request->getPost('max_participants'),
            'max_individual' => $this->request->getPost('max_individual'),
            'max_community' => $this->request->getPost('max_community'),
        ]);

        return redirect()->to('/admin/events/edit/' . $category['event_id'])->with('cat_success', 'Kategori berhasil diperbarui.');
    }

    public function deleteCategory($id)
    {
        $categoryModel = new CategoryModel();
        $eventId = $this->request->getGet('event_id');
        
        $category = $categoryModel->find($id);
        if (!$category) {
            return redirect()->back()->with('cat_error', 'Kategori tidak ditemukan.');
        }

        $categoryModel->delete($id);

        return redirect()->to('/admin/events/edit/' . $eventId)->with('cat_success', 'Kategori berhasil dihapus.');
    }
}
