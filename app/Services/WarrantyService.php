<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductWarrantyPrice;
use App\Models\Warranty;
use Carbon\Carbon;

class WarrantyService
{
    /**
     * Create a new warranty for an order
     * 
     * @param Order $order
     * @return void
     */
    public function createWarrantyForOrder(Order $order): void
    {
        // Only create warranties for paid orders
        if ($order->status !== 'paid') {
            return;
        }
        
        // Create warranty for each eligible product in the order
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            
            if (!$product || !$product->has_warranty) {
                continue;
            }
            
            // Find the default warranty duration
            $warrantyPrice = ProductWarrantyPrice::where('product_id', $product->id)
                ->where('is_default', true)
                ->first();
            
            if (!$warrantyPrice) {
                continue;
            }
            
            // Create the warranty
            Warranty::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addMonths($warrantyPrice->months),
                'is_active' => true,
            ]);
        }
    }

    /**
     * Extend a warranty for additional months or using a warranty price object
     * 
     * @param Warranty $warranty
     * @param int|\App\Models\ProductWarrantyPrice $monthsOrPrice
     * @param Order|null $order
     * @return array
     */
    public function extendWarranty(Warranty $warranty, $monthsOrPrice, ?Order $order = null): array
    {
        // Determine if we received months (int) or a warranty price object
        $warrantyPrice = null;
        $months = 0;
        
        if ($monthsOrPrice instanceof ProductWarrantyPrice) {
            $warrantyPrice = $monthsOrPrice;
            $months = (int)$warrantyPrice->months;
        } else {
            $months = (int)$monthsOrPrice;
            // Calculate price based on product warranty pricing
            $warrantyPrice = ProductWarrantyPrice::where('product_id', $warranty->product_id)
                ->where('months', $months)
                ->first();
            
            if (!$warrantyPrice) {
                return [
                    'success' => false,
                    'message' => 'No warranty price found for this duration',
                    'extension' => null,
                    'order' => null
                ];
            }
        }
        
        // Store old end date
        $oldEndsAt = $warranty->ends_at;
        
        // Calculate new end date
        $today = Carbon::today();
        if ($warranty->isExpired()) {
            $newDate = $today->copy()->addMonths($months);
        } else {
            $newDate = Carbon::parse($warranty->ends_at)->addMonths($months);
        }
        
        // Update the warranty with the new date
        $warranty->update([
            'ends_at' => $newDate,
            'is_active' => true
        ]);
        
        // Record the extension
        $extension = $warranty->extensions()->create([
            'old_ends_at' => $oldEndsAt,
            'new_ends_at' => $newDate,
            'price' => $warrantyPrice->price,
            'order_id' => $order ? $order->id : null,
        ]);        return [
            'success' => true,
            'extension' => $extension,
            'order' => $order,
            'warranty' => $warranty->refresh()
        ];
    }

    /**
     * Check if a warranty is valid for a product
     * 
     * @param Product $product
     * @param int $userId
     * @return bool
     */
    public function hasValidWarranty(Product $product, int $userId): bool
    {
        return Warranty::whereHas('order', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->where('ends_at', '>=', now())
            ->exists();
    }

    /**
     * Get expiring warranties (within 30 days)
     * 
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getExpiringWarranties(int $userId)
    {
        $thirtyDaysFromNow = now()->addDays(30);
        
        return Warranty::whereHas('order', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('is_active', true)
            ->where('ends_at', '>=', now())
            ->where('ends_at', '<=', $thirtyDaysFromNow)
            ->get();
    }
}
