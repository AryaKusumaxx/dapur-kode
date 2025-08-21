<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warranty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the order that owns the warranty.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that is covered by the warranty.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Get the order item associated with this warranty.
     */
    public function orderItem()
    {
        return $this->hasOne(OrderItem::class, 'order_id', 'order_id')
                    ->where('product_id', $this->product_id);
    }

    /**
     * Get the warranty extensions.
     */
    public function extensions(): HasMany
    {
        return $this->hasMany(WarrantyExtension::class);
    }

    /**
     * Check if the warranty is expired.
     */
    public function isExpired(): bool
    {
        return $this->ends_at->isPast();
    }

    /**
     * Check if the warranty can be extended.
     */
    public function canBeExtended(): bool
    {
        // Can be extended if it's active and either not expired or expired within the last 30 days
        return $this->is_active && 
               ($this->ends_at->isFuture() || $this->ends_at->diffInDays(now()) <= 30);
    }

    /**
     * Get the warranty status.
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        // Warn if warranty is ending soon (within 30 days)
        if ($this->ends_at->diffInDays(now()) <= 30) {
            return 'ending_soon';
        }

        return 'active';
    }

    /**
     * Get the remaining days of the warranty.
     */
    public function getRemainingDaysAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->ends_at);
    }
}
