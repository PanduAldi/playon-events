<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveQuotaFromEvents extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('quota', 'events')) {
            $this->forge->dropColumn('events', 'quota');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('quota', 'events')) {
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
    }
}
