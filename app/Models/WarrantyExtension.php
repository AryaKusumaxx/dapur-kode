<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyExtension extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'warranty_id',
        'old_ends_at',
        'new_ends_at',
        'price',
        'order_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_ends_at' => 'date',
        'new_ends_at' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * Get the warranty being extended.
     */
    public function warranty(): BelongsTo
    {
        return $this->belongsTo(Warranty::class);
    }

    /**
     * Get the order that paid for this extension.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
