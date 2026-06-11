<?php

namespace App\Controllers;

use App\Models\JerseyModel;
use App\Models\JerseySizeModel;
use App\Models\JerseyImageModel;
use App\Models\JerseyOrderModel;
use CodeIgniter\Controller;

class JerseyController extends BaseController
{
    protected $jerseyModel;
    protected $sizeModel;
    protected $imageModel;
    protected $orderModel;

    public function __construct()
    {
        $this->jerseyModel = new JerseyModel();
        $this->sizeModel = new JerseySizeModel();
        $this->imageModel = new JerseyImageModel();
        $this->orderModel = new JerseyOrderModel();
    }

    public function index()
    {
        $data['jerseys'] = $this->jerseyModel->where('status', 'active')->findAll();
        // Add primary image to each jersey
        foreach ($data['jerseys'] as &$jersey) {
            $primaryImage = $this->imageModel->where('jersey_id', $jersey['id'])->where('is_primary', 1)->first();
            if (!$primaryImage) {
                $primaryImage = $this->imageModel->where('jersey_id', $jersey['id'])->first();
            }
            $jersey['primary_image'] = $primaryImage ? $primaryImage['image_path'] : 'no-image.jpg';
        }
        return view('jerseys/index', $data);
    }

    public function view($slug)
    {
        $jersey = $this->jerseyModel->where('slug', $slug)->first();
        if (!$jersey) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['jersey'] = $jersey;
        $data['sizes'] = $this->sizeModel->where('jersey_id', $jersey['id'])->findAll();
        $data['images'] = $this->imageModel->where('jersey_id', $jersey['id'])->findAll();

        return view('jerseys/view', $data);
    }

    public function checkout()
    {
        $jerseyId = $this->request->getPost('jersey_id');
        $sizeId = $this->request->getPost('size_id');
        $quantity = $this->request->getPost('quantity', 1);

        $jersey = $this->jerseyModel->find($jerseyId);
        $size = $this->sizeModel->find($sizeId);

        if (!$jersey || !$size || $size['jersey_id'] != $jerseyId) {
            return redirect()->back()->with('error', 'Invalid selection.');
        }

        if ($size['stock'] < $quantity) {
            return redirect()->back()->with('error', 'Sorry, requested stock is not available.');
        }

        // Handle payment proof
        $file = $this->request->getFile('payment_proof');
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'Please upload payment proof.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/payments', $newName);

        $orderNumber = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));

        $orderData = [
            'order_number'     => $orderNumber,
            'jersey_id'        => $jerseyId,
            'size_id'          => $sizeId,
            'quantity'         => $quantity,
            'total_price'      => $jersey['price'] * $quantity,
            'customer_name'    => $this->request->getPost('customer_name'),
            'customer_email'   => $this->request->getPost('customer_email'),
            'customer_phone'   => $this->request->getPost('customer_phone'),
            'shipping_address' => $this->request->getPost('shipping_address'),
            'payment_proof'    => $newName,
            'status'           => 'pending',
        ];

        $this->orderModel->insert($orderData);

        return view('jerseys/success', ['order_number' => $orderNumber]);
    }
}
