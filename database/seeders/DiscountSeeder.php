<?php

namespace Database\Seeders;

use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Global discounts (not tied to a specific product)
        Discount::create([
            'code' => 'WELCOME25',
            'description' => 'Diskon 25% untuk pelanggan baru',
            'type' => 'percentage',
            'value' => 25,
            'product_id' => null, // applies to all products
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(3),
            'max_uses' => 100,
            'used_count' => 0,
            'is_active' => true,
        ]);

        Discount::create([
            'code' => 'FLAT100K',
            'description' => 'Potongan Rp 100.000 untuk semua produk',
            'type' => 'fixed',
            'value' => 100000,
            'product_id' => null, // applies to all products
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(1),
            'max_uses' => 50,
            'used_count' => 0,
            'is_active' => true,
        ]);
        
        // Special discount for a specific product (ID = 1, first seeded product)
        Discount::create([
            'code' => 'WEBSITE30',
            'description' => 'Diskon 30% untuk paket website company profile',
            'type' => 'percentage',
            'value' => 30,
            'product_id' => 1, // Paket Website Company Profile
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addWeeks(2),
            'max_uses' => 20,
            'used_count' => 0,
            'is_active' => true,
        ]);
        
        // Special discount for another product (ID = 3, third seeded product)
        Discount::create([
            'code' => 'SERVER20',
            'description' => 'Diskon 20% untuk jasa instalasi server',
            'type' => 'percentage',
            'value' => 20,
            'product_id' => 3, // Jasa Instalasi & Konfigurasi Server
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(2),
            'max_uses' => 15,
            'used_count' => 0,
            'is_active' => true,
        ]);
        
        // Inactive discount (for testing purposes)
        Discount::create([
            'code' => 'EXPIRED50',
            'description' => 'Diskon 50% yang sudah tidak aktif',
            'type' => 'percentage',
            'value' => 50,
            'product_id' => null,
            'starts_at' => Carbon::now()->subMonths(2),
            'expires_at' => Carbon::now()->subMonths(1),
            'max_uses' => 10,
            'used_count' => 10,
            'is_active' => false,
        ]);
    }
}
