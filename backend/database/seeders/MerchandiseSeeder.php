<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchandise;

class MerchandiseSeeder extends Seeder
{
    public function run()
    {
        Merchandise::create([
            'merchandise_id' => 1,
            'name' => 'Black Pink Cotton Tee',
            'price' => 38.00,
            'description' => 'Comfortable black cotton t-shirt with pink design',
            'image_url' => '../assets/images/merchant1.png',
            'sizes_inventory' => [
                'S' => 50,
                'M' => 50,
                'L' => 50,
                'XL' => 50
            ],
            'is_active' => true
        ]);

        Merchandise::create([
            'merchandise_id' => 2,
            'name' => 'White Pink Cotton Tee',
            'price' => 38.00,
            'description' => 'Comfortable white cotton t-shirt with pink design',
            'image_url' => '../assets/images/merchant2.png',
            'sizes_inventory' => [
                'S' => 50,
                'M' => 50,
                'L' => 50,
                'XL' => 50
            ],
            'is_active' => true
        ]);
    }
} 