<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegistrationTypeToRegistrations extends Migration
{
    public function up()
    {
        $fields = [
            'registration_type' => [
                'type' => 'ENUM',
                'constraint' => ['individual', 'community'],
                'default' => 'individual',
                'null' => false,
            ],
        ];

        $this->forge->addColumn('registrations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('registrations', 'registration_type');
    }
}
