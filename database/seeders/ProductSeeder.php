<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductWarrantyPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Paket Products
        $this->createPaketProducts();

        // Create Jasa Pasang Products
        $this->createJasaPasangProducts();

        // Create Lepas Products
        $this->createLepasProducts();
    }

    private function createPaketProducts()
    {
        // Paket Website Company Profile
        $product = Product::create([
            'name' => 'Paket Website Company Profile',
            'slug' => 'paket-website-company-profile',
            'description' => 'Paket lengkap pembuatan website company profile untuk perusahaan dengan desain modern dan responsif. Termasuk hosting dan domain untuk 1 tahun pertama.',
            'type' => 'paket',
            'base_price' => 3500000,
            'has_warranty' => true,
        ]);

        // Create variants
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Starter',
            'description' => 'Maksimal 5 halaman dengan template standar',
            'price_adjustment' => -500000,
            'features' => json_encode([
                'Max 5 halaman',
                'Template standar',
                'Free domain (.com/.id)',
                'Hosting 1 tahun',
                'Email bisnis 2 akun',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Business',
            'description' => 'Website perusahaan lengkap dengan CMS',
            'price_adjustment' => 0, // Default price
            'features' => json_encode([
                'Unlimited halaman',
                'Template premium',
                'Free domain (.com/.id)',
                'Hosting 1 tahun',
                'Email bisnis 5 akun',
                'CMS untuk update konten',
                'Google Maps integration',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Enterprise',
            'description' => 'Website dengan fitur custom sesuai kebutuhan',
            'price_adjustment' => 1500000,
            'features' => json_encode([
                'Unlimited halaman',
                'Custom design',
                'Free domain (.com/.id)',
                'Hosting 1 tahun premium',
                'Email bisnis 10 akun',
                'CMS untuk update konten',
                'Google Maps integration',
                'Multi bahasa',
                'Google Analytics setup',
            ]),
        ]);

        // Create warranty prices
        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 500000,
            'is_default' => true,
        ]);

        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 12,
            'price' => 900000,
            'is_default' => false,
        ]);
        
        // Paket Toko Online
        $product = Product::create([
            'name' => 'Paket Toko Online (E-Commerce)',
            'slug' => 'paket-toko-online',
            'description' => 'Paket lengkap pembuatan website toko online dengan sistem pembayaran terintegrasi, manajemen produk, dan laporan penjualan.',
            'type' => 'paket',
            'base_price' => 5000000,
            'has_warranty' => true,
        ]);

        // Create variants
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Basic Store',
            'description' => 'Toko online dengan fitur standar',
            'price_adjustment' => -1000000,
            'features' => json_encode([
                'Max 50 produk',
                'Template standar',
                'Free domain (.com)',
                'Hosting 1 tahun',
                'Manual payment',
                'Laporan penjualan basic',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Premium Store',
            'description' => 'Toko online lengkap dengan payment gateway',
            'price_adjustment' => 0, // Default price
            'features' => json_encode([
                'Unlimited produk',
                'Template premium',
                'Free domain (.com)',
                'Hosting 1 tahun',
                'Payment gateway (Midtrans)',
                'Laporan penjualan lengkap',
                'Integrasi marketplace',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Enterprise Store',
            'description' => 'Toko online dengan fitur custom dan integrasi lengkap',
            'price_adjustment' => 3000000,
            'features' => json_encode([
                'Unlimited produk',
                'Custom design',
                'Free domain (.com)',
                'Hosting 1 tahun premium',
                'Multiple payment gateways',
                'Laporan bisnis dan analitik',
                'Integrasi dengan sistem ERP',
                'Aplikasi mobile',
            ]),
        ]);

        // Create warranty prices
        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 800000,
            'is_default' => true,
        ]);

        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 12,
            'price' => 1500000,
            'is_default' => false,
        ]);
    }

    private function createJasaPasangProducts()
    {
        // Jasa Instalasi & Konfigurasi Server
        $product = Product::create([
            'name' => 'Jasa Instalasi & Konfigurasi Server',
            'slug' => 'jasa-instalasi-konfigurasi-server',
            'description' => 'Jasa professional untuk instalasi, konfigurasi, dan optimasi server untuk kebutuhan aplikasi web, database, dan layanan cloud.',
            'type' => 'jasa_pasang',
            'base_price' => 2500000,
            'has_warranty' => true,
        ]);

        // Create variants
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Basic Setup',
            'description' => 'Instalasi dan konfigurasi dasar',
            'price_adjustment' => -500000,
            'features' => json_encode([
                'Instalasi OS',
                'Konfigurasi firewall',
                'Setup web server',
                'Setup database',
                'Konsultasi 1 jam',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Advanced Setup',
            'description' => 'Konfigurasi lengkap dengan optimasi',
            'price_adjustment' => 0, // Default price
            'features' => json_encode([
                'Instalasi OS',
                'Konfigurasi firewall',
                'Setup web server dengan load balancing',
                'Setup database dengan replikasi',
                'Optimasi performa',
                'Monitoring tools',
                'Konsultasi 3 jam',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Enterprise Setup',
            'description' => 'Konfigurasi full stack dengan high availability',
            'price_adjustment' => 1500000,
            'features' => json_encode([
                'Instalasi OS',
                'Konfigurasi firewall',
                'Setup web server dengan load balancing',
                'Setup database dengan clustering',
                'High availability setup',
                'Backup dan disaster recovery',
                'Monitoring dan alerting system',
                'Security hardening',
                'Dokumentasi lengkap',
                'Konsultasi 10 jam',
            ]),
        ]);

        // Create warranty prices
        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 600000,
            'is_default' => true,
        ]);

        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 12,
            'price' => 1100000,
            'is_default' => false,
        ]);
        
        // Jasa Migrasi Website
        $product = Product::create([
            'name' => 'Jasa Migrasi Website',
            'slug' => 'jasa-migrasi-website',
            'description' => 'Layanan migrasi website dari platform lama ke platform baru atau dari hosting lama ke hosting baru dengan aman tanpa kehilangan data.',
            'type' => 'jasa_pasang',
            'base_price' => 1500000,
            'has_warranty' => true,
        ]);

        // Create variants
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Basic Migration',
            'description' => 'Migrasi website sederhana',
            'price_adjustment' => -500000,
            'features' => json_encode([
                'Migrasi konten',
                'Migrasi database',
                'Setup domain',
                'Testing basic',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Standard Migration',
            'description' => 'Migrasi website dengan optimasi',
            'price_adjustment' => 0, // Default price
            'features' => json_encode([
                'Migrasi konten lengkap',
                'Migrasi database dengan validasi',
                'Setup domain dan DNS',
                'Optimasi performa',
                'Testing menyeluruh',
                'Backup data lama',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Complex Migration',
            'description' => 'Migrasi website kompleks dengan data besar',
            'price_adjustment' => 1000000,
            'features' => json_encode([
                'Migrasi konten lengkap',
                'Migrasi database dengan validasi dan optimasi',
                'Migrasi email',
                'Setup domain, DNS, dan SSL',
                'Optimasi performa',
                'Testing menyeluruh dan QA',
                'Backup data lama dan rencana rollback',
                'Zero downtime migration',
            ]),
        ]);

        // Create warranty prices
        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 400000,
            'is_default' => true,
        ]);

        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 12,
            'price' => 700000,
            'is_default' => false,
        ]);
    }

    private function createLepasProducts()
    {
        // Template Admin Dashboard
        $product = Product::create([
            'name' => 'Template Admin Dashboard',
            'slug' => 'template-admin-dashboard',
            'description' => 'Template admin dashboard profesional dengan tampilan modern dan responsif. Siap digunakan untuk berbagai jenis aplikasi web.',
            'type' => 'lepas',
            'base_price' => 750000,
            'has_warranty' => true,
        ]);

        // Create variants
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'HTML Version',
            'description' => 'Template HTML statis',
            'price_adjustment' => -250000,
            'features' => json_encode([
                'HTML statis dengan CSS',
                '5 halaman template',
                'Dokumentasi basic',
                'Responsive design',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Vue.js Version',
            'description' => 'Template dengan Vue.js',
            'price_adjustment' => 0, // Default price
            'features' => json_encode([
                'Vue.js components',
                '10 halaman template',
                'Dokumentasi lengkap',
                'Responsive design',
                'Dark mode & customizable themes',
                'Chart components',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Laravel + Vue Version',
            'description' => 'Template dengan Laravel dan Vue.js',
            'price_adjustment' => 450000,
            'features' => json_encode([
                'Laravel 12 backend',
                'Vue.js 3 frontend',
                '15 halaman template',
                'Authentication system',
                'API ready',
                'Dokumentasi lengkap',
                'Responsive design',
                'Dark mode & customizable themes',
                'Chart components & datatables',
            ]),
        ]);

        // Create warranty prices
        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 200000,
            'is_default' => true,
        ]);

        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 12,
            'price' => 350000,
            'is_default' => false,
        ]);
        
        // Plugin WordPress E-Commerce
        $product = Product::create([
            'name' => 'Plugin WordPress E-Commerce',
            'slug' => 'plugin-wordpress-ecommerce',
            'description' => 'Plugin WordPress untuk e-commerce dengan fitur lengkap. Mudah diintegrasikan dengan tema WordPress dan plugin lainnya.',
            'type' => 'lepas',
            'base_price' => 1200000,
            'has_warranty' => true,
        ]);

        // Create variants
        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Basic',
            'description' => 'Fitur e-commerce dasar',
            'price_adjustment' => -400000,
            'features' => json_encode([
                'Manajemen produk',
                'Keranjang belanja',
                'Checkout basic',
                'Manual payment',
                'Order tracking',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Standard',
            'description' => 'Fitur e-commerce lengkap',
            'price_adjustment' => 0, // Default price
            'features' => json_encode([
                'Manajemen produk dengan varian',
                'Keranjang belanja',
                'Checkout process',
                'Multiple payment gateways',
                'Order tracking',
                'Customer management',
                'Kupon dan diskon',
                'Integrasi dengan WooCommerce',
            ]),
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Premium',
            'description' => 'Solusi e-commerce lengkap dengan fitur advanced',
            'price_adjustment' => 800000,
            'features' => json_encode([
                'Semua fitur Standard',
                'Membership system',
                'Subscription payments',
                'Marketplace multi vendor',
                'Affiliate program',
                'Integrasi dengan social media',
                'Advanced analytics',
                'API untuk mobile apps',
            ]),
        ]);

        // Create warranty prices
        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 6,
            'price' => 300000,
            'is_default' => true,
        ]);

        ProductWarrantyPrice::create([
            'product_id' => $product->id,
            'months' => 12,
            'price' => 550000,
            'is_default' => false,
        ]);
    }
}
