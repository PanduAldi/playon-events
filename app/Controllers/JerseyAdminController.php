<?php

namespace App\Controllers;

use App\Models\JerseyModel;
use App\Models\JerseySizeModel;
use App\Models\JerseyImageModel;
use App\Models\JerseyOrderModel;
use CodeIgniter\Controller;

class JerseyAdminController extends BaseController
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
        $data['jerseys'] = $this->jerseyModel->findAll();
        return view('admin/jerseys/index', $data);
    }

    public function new()
    {
        return view('admin/jerseys/form');
    }

    public function create()
    {
        $name = $this->request->getPost('name');
        $jerseyData = [
            'name'        => $name,
            'slug'        => url_title($name, '-', true) . '-' . time(),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'status'      => $this->request->getPost('status'),
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $jerseyId = $this->jerseyModel->insert($jerseyData);

            if (!$jerseyId) {
                $error = $this->jerseyModel->errors();
                throw new \Exception('Insert Jersey Error: ' . json_encode($error));
            }

            // Handle Sizes
            $sizes = $this->request->getPost('sizes');
            $stocks = $this->request->getPost('stocks');
            if ($sizes) {
                foreach ($sizes as $index => $sizeName) {
                    if (!empty($sizeName)) {
                        $this->sizeModel->insert([
                            'jersey_id' => $jerseyId,
                            'size_name' => $sizeName,
                            'stock'     => $stocks[$index] ?? 0,
                        ]);
                    }
                }
            }

            // Handle Images
            $imageFiles = $this->request->getFileMultiple('images');
            if ($imageFiles) {
                foreach ($imageFiles as $index => $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $newName = $img->getRandomName();
                        $img->move(FCPATH . 'uploads/jerseys', $newName);

                        $this->imageModel->insert([
                            'jersey_id'  => $jerseyId,
                            'image_path' => $newName,
                            'is_primary' => ($index === 0) ? 1 : 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database Transaction Failed');
            }

            return redirect()->to('/admin/jerseys')->with('success', 'Jersey created successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['jersey'] = $this->jerseyModel->getWithDetails($id);
        if (!$data['jersey']) {
            return redirect()->to('/admin/jerseys')->with('error', 'Jersey not found.');
        }
        return view('admin/jerseys/form', $data);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $name = $this->request->getPost('name');
        $jerseyData = [
            'name'        => $name,
            'slug'        => url_title($name, '-', true),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'status'      => $this->request->getPost('status'),
        ];

        $this->jerseyModel->update($id, $jerseyData);

        // Update Sizes (Delete old ones and add new ones for simplicity in this MVP)
        $this->sizeModel->where('jersey_id', $id)->delete();
        $sizes = $this->request->getPost('sizes');
        $stocks = $this->request->getPost('stocks');
        if ($sizes) {
            foreach ($sizes as $index => $sizeName) {
                if (!empty($sizeName)) {
                    $this->sizeModel->insert([
                        'jersey_id' => $id,
                        'size_name' => $sizeName,
                        'stock'     => $stocks[$index] ?? 0,
                    ]);
                }
            }
        }

        // Handle New Images
        $imageFiles = $this->request->getFileMultiple('images');
        if ($imageFiles) {
            foreach ($imageFiles as $img) {
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(FCPATH . 'uploads/jerseys', $newName);

                    $this->imageModel->insert([
                        'jersey_id'  => $id,
                        'image_path' => $newName,
                        'is_primary' => 0,
                    ]);
                }
            }
        }

        $db->transComplete();

        return redirect()->to('/admin/jerseys')->with('success', 'Jersey updated successfully.');
    }

    public function orders()
    {
        $data['orders'] = $this->orderModel->getOrdersWithDetails();
        return view('admin/jerseys/orders', $data);
    }

    public function confirmOrder($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order || $order['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid order.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Reduce stock
        $size = $this->sizeModel->find($order['size_id']);
        if ($size['stock'] < $order['quantity']) {
            return redirect()->back()->with('error', 'Insufficient stock.');
        }

        $this->sizeModel->update($order['size_id'], [
            'stock' => $size['stock'] - $order['quantity']
        ]);

        // Update order status
        $this->orderModel->update($id, ['status' => 'confirmed']);

        $db->transComplete();

        return redirect()->back()->with('success', 'Order confirmed and stock updated.');
    }
}
