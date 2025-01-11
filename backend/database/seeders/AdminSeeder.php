<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->insert([
            'username' => 'admin123',
            'email' => 'admin@example.com',
            'password_hash' => Hash::make('password123'),
            'created_at' => now(),
            'last_updated_at' => now(),
        ]);
    }
}
