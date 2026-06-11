<?php

namespace App\Models;

use CodeIgniter\Model;

class JerseyModel extends Model
{
    protected $table            = 'jerseys';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'slug', 'description', 'price', 'status'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getWithDetails($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }

        $jersey = $this->find($id);
        if ($jersey) {
            $jersey['sizes'] = (new JerseySizeModel())->where('jersey_id', $id)->findAll();
            $jersey['images'] = (new JerseyImageModel())->where('jersey_id', $id)->findAll();
        }

        return $jersey;
    }
}
