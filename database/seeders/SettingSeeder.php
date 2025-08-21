<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Company Information
        $this->createSetting('company_name', 'DapurKode', 'company', 'Nama perusahaan');
        $this->createSetting('company_address', 'Jl. Programmer No. 123, Kota Digital, Indonesia', 'company', 'Alamat perusahaan');
        $this->createSetting('company_phone', '081234567890', 'company', 'Nomor telepon perusahaan');
        $this->createSetting('company_email', 'info@dapurkode.com', 'company', 'Email perusahaan');
        $this->createSetting('company_website', 'https://dapurkode.com', 'company', 'Website perusahaan');
        $this->createSetting('company_logo', 'images/logo.png', 'company', 'Logo perusahaan');
        $this->createSetting('company_tax_id', '12.345.678.9-012.000', 'company', 'NPWP perusahaan');

        // Invoice Settings
        $this->createSetting('invoice_prefix', 'INV', 'invoice', 'Prefix untuk nomor invoice');
        $this->createSetting('invoice_date_format', 'Ymd', 'invoice', 'Format tanggal pada nomor invoice');
        $this->createSetting('invoice_number_length', '4', 'invoice', 'Panjang angka pada nomor invoice', 'number');
        $this->createSetting('invoice_due_days', '7', 'invoice', 'Jumlah hari jatuh tempo invoice dari tanggal pembuatan', 'number');
        $this->createSetting('invoice_notes', 'Terima kasih atas kepercayaan Anda. Pembayaran dapat dilakukan melalui rekening yang tertera pada invoice.', 'invoice', 'Catatan default pada invoice');

        // Payment Settings
        $this->createSetting('payment_bank_accounts', json_encode([
            [
                'bank_name' => 'Bank BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT DapurKode Digital',
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'PT DapurKode Digital',
            ],
        ]), 'payment', 'Daftar rekening bank untuk pembayaran', 'json');
        
        $this->createSetting('payment_verification_auto', 'false', 'payment', 'Otomatis verifikasi pembayaran (untuk development)', 'boolean');

        // Notification Settings
        $this->createSetting('admin_notification_email', 'admin@dapurkode.com', 'notification', 'Email untuk notifikasi admin');
        
        // WhatsApp Templates
        $this->createSetting(
            'whatsapp_template_new_order', 
            'Halo {{name}}, terima kasih telah melakukan pemesanan di DapurKode. Nomor pesanan: {{order_number}} dengan total Rp {{total}}. Silakan lakukan pembayaran dan upload bukti pembayaran melalui: {{invoice_url}}',
            'notification',
            'Template WhatsApp untuk pesanan baru'
        );
        
        $this->createSetting(
            'whatsapp_template_payment_verified', 
            'Halo {{name}}, pembayaran untuk pesanan #{{order_number}} dengan nomor invoice {{invoice_number}} sebesar Rp {{amount}} telah kami verifikasi. Terima kasih atas kepercayaan Anda.',
            'notification',
            'Template WhatsApp untuk pembayaran terverifikasi'
        );
        
        $this->createSetting(
            'whatsapp_template_warranty_expiring', 
            'Halo {{name}}, garansi untuk produk {{product_name}} akan berakhir pada tanggal {{expiry_date}}. Untuk memperpanjang garansi, silakan klik: {{extension_url}}',
            'notification',
            'Template WhatsApp untuk garansi akan berakhir'
        );
        
        // System Settings
        $this->createSetting('tax_percentage', '11', 'system', 'Persentase pajak yang dikenakan (PPN)', 'number');
        $this->createSetting('maintenance_mode', 'false', 'system', 'Mode pemeliharaan website', 'boolean');
    }
    
    /**
     * Create a setting record
     * 
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $description
     * @param string $type
     * @param bool $isPublic
     * @return void
     */
    private function createSetting(string $key, $value, string $group, string $description, string $type = 'text', bool $isPublic = false): void
    {
        Setting::create([
            'key' => $key,
            'value' => is_array($value) || is_object($value) ? json_encode($value) : $value,
            'group' => $group,
            'description' => $description,
            'type' => $type,
            'is_public' => $isPublic,
        ]);
    }
}
