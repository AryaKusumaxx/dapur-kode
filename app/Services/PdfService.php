<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Generate an invoice PDF
     * 
     * @param Invoice $invoice
     * @return string Path to the PDF file
     */
    public function generateInvoicePdf(Invoice $invoice): string
    {
        // Get company details from settings
        $companyName = Setting::getValue('company_name', 'DapurKode');
        $companyAddress = Setting::getValue('company_address', '');
        $companyPhone = Setting::getValue('company_phone', '');
        $companyEmail = Setting::getValue('company_email', '');
        
        $order = $invoice->order;
        $user = $order->user;
        
        // Generate the PDF
        $pdf = PDF::loadView('pdfs.invoice', [
            'invoice' => $invoice,
            'order' => $order,
            'user' => $user,
            'company' => [
                'name' => $companyName,
                'address' => $companyAddress,
                'phone' => $companyPhone,
                'email' => $companyEmail,
            ],
        ]);
        
        // Define file path
        $fileName = 'invoice_' . $invoice->invoice_number . '.pdf';
        $filePath = 'invoices/' . $fileName;
        
        // Save the PDF
        Storage::put('public/' . $filePath, $pdf->output());
        
        return $filePath;
    }
    
    /**
     * Generate a warranty certificate PDF
     * 
     * @param \App\Models\Warranty $warranty
     * @return string Path to the PDF file
     */
    public function generateWarrantyCertificatePdf($warranty): string
    {
        // Get company details from settings
        $companyName = Setting::getValue('company_name', 'DapurKode');
        $companyAddress = Setting::getValue('company_address', '');
        
        $order = $warranty->order;
        $product = $warranty->product;
        $user = $order->user;
        
        // Generate the PDF
        $pdf = PDF::loadView('pdfs.warranty', [
            'warranty' => $warranty,
            'product' => $product,
            'user' => $user,
            'company' => [
                'name' => $companyName,
                'address' => $companyAddress,
            ],
        ]);
        
        // Define file path
        $fileName = 'warranty_' . $product->id . '_' . $order->id . '.pdf';
        $filePath = 'warranties/' . $fileName;
        
        // Save the PDF
        Storage::put('public/' . $filePath, $pdf->output());
        
        return $filePath;
    }
}
