<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitSchema extends Migration
{
    public function up()
    {
        // 1. events
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 200],
            'description' => ['type' => 'TEXT', 'null' => true],
            'event_date' => ['type' => 'DATETIME'],
            'location' => ['type' => 'VARCHAR', 'constraint' => 300],
            'maps_url' => ['type' => 'TEXT', 'null' => true],
            'banner_image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'registration_open' => ['type' => 'DATETIME'],
            'registration_close' => ['type' => 'DATETIME'],
            'status' => ['type' => 'ENUM', 'constraint' => ['draft', 'active', 'closed', 'finished'], 'default' => 'draft'],
            'event_type' => ['type' => 'ENUM', 'constraint' => ['free', 'paid'], 'default' => 'paid'],
            'allow_waitlist' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('events');

        // 2. event_categories
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'code' => ['type' => 'VARCHAR', 'constraint' => 20],
            'max_participants' => ['type' => 'INT', 'constraint' => 11],
            'registered_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'fee' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'min_age' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'max_age' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_categories');

        // 3. participants
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'full_name' => ['type' => 'VARCHAR', 'constraint' => 200],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150],
            'birth_date' => ['type' => 'DATE'],
            'gender' => ['type' => 'ENUM', 'constraint' => ['M', 'F']],
            'shirt_size' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'club_name' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'emergency_contact' => ['type' => 'VARCHAR', 'constraint' => 200],
            'medical_notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('participants');

        // 4. registrations
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'participant_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'bib_number' => ['type' => 'VARCHAR', 'constraint' => 30],
            'qr_token' => ['type' => 'VARCHAR', 'constraint' => 64],
            'payment_status' => ['type' => 'ENUM', 'constraint' => ['free', 'unpaid', 'paid'], 'default' => 'unpaid'],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'confirmed', 'attended', 'cancelled'], 'default' => 'pending'],
            'registered_at' => ['type' => 'DATETIME'],
            'attended_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('participant_id', 'participants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'event_categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['participant_id', 'event_id', 'category_id']);
        $this->forge->createTable('registrations');

        // 5. registration_sessions
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'session_token' => ['type' => 'VARCHAR', 'constraint' => 64],
            'participant_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'total_events' => ['type' => 'INT', 'constraint' => 11],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending', 'completed', 'expired'], 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME'],
            'expires_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('participant_id', 'participants', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('registration_sessions');
    }

    public function down()
    {
        $this->forge->dropTable('registration_sessions', true);
        $this->forge->dropTable('registrations', true);
        $this->forge->dropTable('participants', true);
        $this->forge->dropTable('event_categories', true);
        $this->forge->dropTable('events', true);
    }
}
