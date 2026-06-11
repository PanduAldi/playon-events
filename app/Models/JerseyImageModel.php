<?php

namespace App\Models;

use CodeIgniter\Model;

class JerseyImageModel extends Model
{
    protected $table            = 'jersey_images';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['jersey_id', 'image_path', 'is_primary'];

    protected $useTimestamps = false;
}
