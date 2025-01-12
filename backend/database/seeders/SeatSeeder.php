<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run()
    {
        // Left section
        foreach (range(1, 30) as $i) {
            Seat::create([
                'seat_number' => 'L' . $i,
                'is_booked' => false
            ]);
        }

        // Center section
        foreach (range(1, 30) as $i) {
            Seat::create([
                'seat_number' => 'C' . $i,
                'is_booked' => false
            ]);
        }

        // Right section
        foreach (range(1, 30) as $i) {
            Seat::create([
                'seat_number' => 'R' . $i,
                'is_booked' => false
            ]);
        }
    }
}