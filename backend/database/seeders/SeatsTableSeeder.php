<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatsTableSeeder extends Seeder
{
    public function run()
    {
        $rows = range('A', 'O'); // A to O (15 rows)
        $columns = range(1, 24); // 1 to 24 (24 seats per row)
        $event_id = 1; // You can modify this as needed

        foreach ($rows as $row) {
            foreach ($columns as $column) {
                DB::table('seats')->insert([
                    'seat_id' => $row . $column,
                    'event_id' => $event_id,
                    'is_booked' => false,
                    'enabled_flag' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
} 