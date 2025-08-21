<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\Product;

class DiscountValidator
{
    /**
     * Validate if a discount code is valid for a product
     * 
     * @param string $code
     * @param Product $product
     * @return array
     */
    public function validate(string $code, Product $product): array
    {
        // Find the discount
        $discount = Discount::where('code', $code)->first();
        
        // If discount not found
        if (!$discount) {
            return [
                'valid' => false,
                'message' => 'Kode diskon tidak ditemukan.',
                'discount' => null
            ];
        }
        
        // Check if discount is active
        if (!$discount->is_active) {
            return [
                'valid' => false,
                'message' => 'Kode diskon tidak aktif.',
                'discount' => null
            ];
        }
        
        // Check if discount is applicable to this product
        if ($discount->product_id !== null && $discount->product_id != $product->id) {
            return [
                'valid' => false,
                'message' => 'Kode diskon tidak berlaku untuk produk ini.',
                'discount' => null
            ];
        }
        
        // Check if discount is within valid date range
        if ($discount->starts_at && $discount->starts_at->isFuture()) {
            return [
                'valid' => false,
                'message' => 'Kode diskon belum aktif.',
                'discount' => null
            ];
        }
        
        if ($discount->expires_at && $discount->expires_at->isPast()) {
            return [
                'valid' => false,
                'message' => 'Kode diskon sudah kadaluarsa.',
                'discount' => null
            ];
        }
        
        // Check usage limit
        if ($discount->max_uses !== null && $discount->used_count >= $discount->max_uses) {
            return [
                'valid' => false,
                'message' => 'Kode diskon sudah mencapai batas penggunaan.',
                'discount' => null
            ];
        }
        
        // Calculate the discount amount
        $amount = $this->calculateDiscountAmount($discount, (float) $product->base_price);

        // Discount is valid
        return [
            'valid' => true,
            'message' => 'Kode diskon berhasil diterapkan.',
            'discount' => $discount,
            'amount' => $amount
        ];
    }
    
    /**
     * Calculate the discount amount for a product
     * 
     * @param Discount $discount
     * @param float $price
     * @return float
     */
    public function calculateDiscountAmount(Discount $discount, float $price): float
    {
        if ($discount->type === 'percentage') {
            return round(($discount->value / 100) * $price, 2);
        }
        
        // For fixed discounts, don't discount more than the price
        return min($discount->value, $price);
    }
    
    /**
     * Increment the usage count of a discount
     * 
     * @param Discount $discount
     * @return void
     */
    public function incrementUsage(Discount $discount): void
    {
        $discount->used_count += 1;
        $discount->save();
    }
}
