<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $eventModel = new EventModel();
        $categoryModel = new CategoryModel();
        
        $events = $eventModel->getActiveEvents();
        
        foreach($events as &$event) {
            $event['categories'] = $categoryModel->getCategoriesByEvent($event['id']);
        }
        
        $data = [
            'events' => $events
        ];
        
        return view('public/home', $data);
    }

    public function emailTest()
    {
        $data = [
            'status' => session()->getFlashdata('status'),
            'message' => session()->getFlashdata('message'),
            'debug' => session()->getFlashdata('debug'),
        ];

        return view('public/email_test', $data);
    }

    public function sendEmailTest()
    {
        $toEmail = $this->request->getPost('to_email');
        $subject = $this->request->getPost('subject') ?: 'Tes Email Playon Events';
        $message = $this->request->getPost('message') ?: "Ini adalah percobaan pengiriman email dari Playon Events. Jika Anda menerima email ini, konfigurasi SMTP berhasil.";

        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            session()->setFlashdata('status', 'error');
            session()->setFlashdata('message', 'Alamat email tujuan tidak valid. Gunakan format email yang benar.');
            return redirect()->to('/email-test');
        }

        try {
            $result = $this->sendMail($toEmail, $subject, $message);

            if ($result['success']) {
                session()->setFlashdata('status', 'success');
                session()->setFlashdata('message', 'Email berhasil dikirim ke ' . esc($toEmail) . '.');
                session()->setFlashdata('debug', $result['debug']);
            } else {
                session()->setFlashdata('status', 'error');
                session()->setFlashdata('message', 'Gagal mengirim email: ' . $result['error']);
                session()->setFlashdata('debug', $result['debug']);
            }
        } catch (\Exception $e) {
            session()->setFlashdata('status', 'error');
            session()->setFlashdata('message', 'Terjadi kesalahan saat mengirim email: ' . $e->getMessage());
            session()->setFlashdata('debug', $e->getTraceAsString());
        }

        return redirect()->to('/email-test');
    }
}
