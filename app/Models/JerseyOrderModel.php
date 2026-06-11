<?php

namespace App\Models;

use CodeIgniter\Model;

class JerseyOrderModel extends Model
{
    protected $table            = 'jersey_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'order_number', 'jersey_id', 'size_id', 'quantity', 'total_price',
        'customer_name', 'customer_email', 'customer_phone', 'shipping_address',
        'payment_proof', 'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getOrdersWithDetails()
    {
        return $this->select('jersey_orders.*, jerseys.name as jersey_name, jersey_sizes.size_name')
                    ->join('jerseys', 'jerseys.id = jersey_orders.jersey_id')
                    ->join('jersey_sizes', 'jersey_sizes.id = jersey_orders.size_id')
                    ->orderBy('jersey_orders.created_at', 'DESC')
                    ->findAll();
    }
}
