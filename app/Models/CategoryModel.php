<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'event_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['event_id', 'name', 'code', 'max_participants', 'registered_count', 'fee', 'min_age', 'max_age'];

    public function getCategoriesByEvent($eventId)
    {
        return $this->where('event_id', $eventId)->findAll();
    }
}
