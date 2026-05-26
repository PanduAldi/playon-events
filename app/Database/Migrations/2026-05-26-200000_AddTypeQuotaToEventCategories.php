<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeQuotaToEventCategories extends Migration
{
    public function up()
    {
        $fields = [
            'max_individual' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'max_community' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
        ];

        $this->forge->addColumn('event_categories', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('event_categories', ['max_individual', 'max_community']);
    }
}
