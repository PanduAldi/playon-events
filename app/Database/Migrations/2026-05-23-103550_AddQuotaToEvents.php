<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuotaToEvents extends Migration
{
    public function up()
    {
        $this->forge->addColumn('events', [
            'quota' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'allow_waitlist',
                'null' => false,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('events', 'quota');
    }
}
