<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Setting;

class InvoiceNumberService
{
    /**
     * Generate a unique invoice number based on settings
     * 
     * @return string
     */
    public function generate(): string
    {
        // Get settings or use defaults
        $prefix = Setting::getValue('invoice_prefix', 'INV');
        $dateFormat = Setting::getValue('invoice_date_format', 'Ymd');
        $numberLength = Setting::getValue('invoice_number_length', 4);
        
        // Generate date component
        $date = date($dateFormat);
        
        // Find highest invoice number with same prefix and date
        $latestInvoice = Invoice::where('invoice_number', 'LIKE', "{$prefix}-{$date}-%")
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = 1;
        
        if ($latestInvoice) {
            // Extract the number part from the latest invoice
            $parts = explode('-', $latestInvoice->invoice_number);
            $lastNumber = (int) end($parts);
            $nextNumber = $lastNumber + 1;
        }
        
        // Format the number part with leading zeros
        $formattedNumber = str_pad($nextNumber, $numberLength, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$date}-{$formattedNumber}";
    }
}
