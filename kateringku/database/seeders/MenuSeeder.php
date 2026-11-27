<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Nasi Kotak Ayam Bakar',
                'category' => 'makanan',
                'description' => 'Nasi putih, ayam bakar madu, sambal, lalapan, tahu, tempe, dan kerupuk.',
                'price' => 28000,
                'min_order' => 10,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Nasi Kotak Rendang Spesial',
                'category' => 'makanan',
                'description' => 'Nasi putih, rendang daging sapi, telur balado, sayur nangka, dan sambal ijo.',
                'price' => 35000,
                'min_order' => 10,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Tumpeng Versi 1',
                'category' => 'makanan',
                'description' => 'Nasi kuning tumpeng mini (10 porsi), ayam goreng, perkedel, orek tempe, urap, dan telur rawis.',
                'price' => 350000,
                'min_order' => 1,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Tumpeng Versi 2',
                'category' => 'makanan',
                'description' => 'Nasi kuning tumpeng sedang (20 porsi), ayam bakar, empal daging, sambal goreng ati, mie goreng, dan lalapan.',
                'price' => 550000,
                'min_order' => 1,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Tumpeng Versi 3',
                'category' => 'makanan',
                'description' => 'Nasi kuning tumpeng besar (30 porsi), komplit lauk nusantara premium untuk acara spesial.',
                'price' => 750000,
                'min_order' => 1,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Es Teh Manis',
                'category' => 'minuman',
                'description' => 'Teh melati seduh segar dengan gula asli dalam kemasan cup higienis.',
                'price' => 5000,
                'min_order' => 10,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Es Jeruk Peras',
                'category' => 'minuman',
                'description' => 'Jeruk peras segar pilihan dengan es batu dalam kemasan cup higienis.',
                'price' => 8000,
                'min_order' => 10,
                'image' => null,
                'is_active' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name']],
                $menu
            );
        }
    }
}
