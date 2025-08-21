<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send an email notification
     * 
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param array $data
     * @return bool
     */
    public function sendEmail(string $to, string $subject, string $template, array $data = []): bool
    {
        // In a real implementation, this would use Laravel's Mail facade
        // For this demo, we'll just log the email details
        Log::info('Email notification sent', [
            'to' => $to,
            'subject' => $subject,
            'template' => $template,
            'data' => $data,
        ]);
        
        return true;
    }
    
    /**
     * Send a WhatsApp notification (simulation)
     * 
     * @param string $phoneNumber
     * @param string $templateKey
     * @param array $data
     * @return bool
     */
    public function sendWhatsApp(string $phoneNumber, string $templateKey, array $data = []): bool
    {
        // Get the template from settings
        $template = Setting::getValue('whatsapp_template_' . $templateKey, '');
        
        if (empty($template)) {
            Log::warning('WhatsApp template not found', ['template_key' => $templateKey]);
            return false;
        }
        
        // Replace variables in template
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        
        // In a real implementation, this would integrate with a WhatsApp API
        // For this demo, we'll just log the message details
        Log::info('WhatsApp notification sent', [
            'phone' => $phoneNumber,
            'template_key' => $templateKey,
            'message' => $template,
            'data' => $data,
        ]);
        
        return true;
    }
    
    /**
     * Notify customer about new order
     * 
     * @param \App\Models\Order $order
     * @return void
     */
    public function notifyNewOrder($order): void
    {
        $user = $order->user;
        
        // Email notification
        $this->sendEmail(
            $user->email,
            'Pesanan Baru #' . $order->order_number,
            'emails.new_order',
            [
                'order' => $order,
                'user' => $user,
            ]
        );
        
        // WhatsApp notification if phone number is available
        if ($user->phone) {
            $this->sendWhatsApp(
                $user->phone,
                'new_order',
                [
                    'name' => $user->name,
                    'order_number' => $order->order_number,
                    'total' => number_format($order->total, 0, ',', '.'),
                    'invoice_url' => route('customer.invoices.show', $order->invoice->id),
                ]
            );
        }
    }
    
    /**
     * Notify admin about new payment
     * 
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function notifyNewPayment($payment): void
    {
        // Get admin email from settings
        $adminEmail = Setting::getValue('admin_notification_email', '');
        
        if (empty($adminEmail)) {
            return;
        }
        
        $invoice = $payment->invoice;
        $order = $invoice->order;
        $user = $order->user;
        
        // Email notification
        $this->sendEmail(
            $adminEmail,
            'Pembayaran Baru #' . $invoice->invoice_number,
            'emails.new_payment',
            [
                'payment' => $payment,
                'invoice' => $invoice,
                'order' => $order,
                'user' => $user,
            ]
        );
    }
    
    /**
     * Notify customer about payment verification
     * 
     * @param \App\Models\Payment $payment
     * @return void
     */
    public function notifyPaymentVerified($payment): void
    {
        $invoice = $payment->invoice;
        $order = $invoice->order;
        $user = $order->user;
        
        // Email notification
        $this->sendEmail(
            $user->email,
            'Pembayaran Terverifikasi #' . $invoice->invoice_number,
            'emails.payment_verified',
            [
                'payment' => $payment,
                'invoice' => $invoice,
                'order' => $order,
                'user' => $user,
            ]
        );
        
        // WhatsApp notification if phone number is available
        if ($user->phone) {
            $this->sendWhatsApp(
                $user->phone,
                'payment_verified',
                [
                    'name' => $user->name,
                    'order_number' => $order->order_number,
                    'invoice_number' => $invoice->invoice_number,
                    'amount' => number_format($payment->amount, 0, ',', '.'),
                ]
            );
        }
    }
    
    /**
     * Notify customer about warranty expiration
     * 
     * @param \App\Models\Warranty $warranty
     * @return void
     */
    public function notifyWarrantyExpiration($warranty): void
    {
        $order = $warranty->order;
        $user = $order->user;
        $product = $warranty->product;
        
        // Email notification
        $this->sendEmail(
            $user->email,
            'Garansi Akan Berakhir - ' . $product->name,
            'emails.warranty_expiring',
            [
                'warranty' => $warranty,
                'product' => $product,
                'user' => $user,
            ]
        );
        
        // WhatsApp notification if phone number is available
        if ($user->phone) {
            $this->sendWhatsApp(
                $user->phone,
                'warranty_expiring',
                [
                    'name' => $user->name,
                    'product_name' => $product->name,
                    'expiry_date' => $warranty->ends_at->format('d F Y'),
                    'extension_url' => route('customer.warranties.extend', $warranty->id),
                ]
            );
        }
    }
}
