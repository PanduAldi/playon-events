<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // 1. Seed events
        $eventData = [
            [
                'slug' => 'brebes-run-2026',
                'name' => 'Brebes Run 2026',
                'description' => 'Event lari tahunan di Brebes untuk merayakan semangat komunitas.',
                'event_date' => '2026-08-17 06:00:00',
                'location' => 'Alun-Alun Brebes',
                'maps_url' => 'https://maps.app.goo.gl/placeholder',
                'banner_image' => 'default-banner.jpg',
                'registration_open' => '2026-06-01 00:00:00',
                'registration_close' => '2026-07-31 23:59:59',
                'status' => 'active',
                'event_type' => 'paid',
                'allow_waitlist' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'playon-charity-run',
                'name' => 'Playon Charity Run',
                'description' => 'Lari amal untuk donasi korban bencana alam.',
                'event_date' => '2026-09-01 06:30:00',
                'location' => 'Pantai Randusanga',
                'maps_url' => 'https://maps.app.goo.gl/placeholder',
                'banner_image' => null,
                'registration_open' => '2026-06-15 00:00:00',
                'registration_close' => '2026-08-15 23:59:59',
                'status' => 'active',
                'event_type' => 'free',
                'allow_waitlist' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        $this->db->table('events')->insertBatch($eventData);

        // 2. Seed event_categories
        $categoryData = [
            // Brebes Run 2026 (Paid)
            [
                'event_id' => 1,
                'name' => '5K Fun Run',
                'code' => '5K',
                'max_participants' => 500,
                'registered_count' => 0,
                'fee' => 100000,
                'min_age' => 12,
                'max_age' => null,
            ],
            [
                'event_id' => 1,
                'name' => '10K Challenge',
                'code' => '10K',
                'max_participants' => 300,
                'registered_count' => 0,
                'fee' => 150000,
                'min_age' => 15,
                'max_age' => null,
            ],
            // Playon Charity Run (Free)
            [
                'event_id' => 2,
                'name' => '3K Family Walk/Run',
                'code' => '3K',
                'max_participants' => 1000,
                'registered_count' => 0,
                'fee' => 0,
                'min_age' => null,
                'max_age' => null,
            ]
        ];
        $this->db->table('event_categories')->insertBatch($categoryData);
    }
}
