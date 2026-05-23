<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['slug', 'name', 'description', 'event_date', 'location', 'maps_url', 'banner_image', 'registration_open', 'registration_close', 'status', 'event_type', 'allow_waitlist', 'quota'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    public function getEventBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function getActiveEvents()
    {
        return $this->where('status', 'active')
                    ->orderBy('event_date', 'ASC')
                    ->findAll();
    }
}
