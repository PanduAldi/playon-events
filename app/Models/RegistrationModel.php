<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table            = 'registrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'participant_id', 'event_id', 'category_id', 'bib_number', 
        'qr_token', 'payment_status', 'status', 'registration_type', 'registered_at', 'attended_at'
    ];

    protected $useTimestamps = false;
}
