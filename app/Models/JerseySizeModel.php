<?php

namespace App\Models;

use CodeIgniter\Model;

class JerseySizeModel extends Model
{
    protected $table            = 'jersey_sizes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['jersey_id', 'size_name', 'stock'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
