<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'base_price',
        'has_warranty',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'has_warranty' => 'boolean',
    ];

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the warranty prices for the product.
     */
    public function warrantyPrices(): HasMany
    {
        return $this->hasMany(ProductWarrantyPrice::class);
    }

    /**
     * Get the default warranty price for the product.
     */
    public function defaultWarrantyPrice()
    {
        return $this->warrantyPrices()->where('is_default', true)->first();
    }

    /**
     * Get the discounts for this product.
     */
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    /**
     * Get the active discounts for this product.
     */
    public function activeDiscounts()
    {
        return $this->discounts()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('max_uses')
                      ->orWhereRaw('used_count < max_uses');
            });
    }
}
