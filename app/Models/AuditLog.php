<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'model_type',
        'model_id',
        'event',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the changes made in the audit.
     */
    public function getChangesAttribute()
    {
        $changes = [];

        if (!$this->old_values && !$this->new_values) {
            return $changes;
        }

        // For creations, all new values are changes
        if ($this->event === 'created') {
            foreach ($this->new_values as $key => $value) {
                $changes[$key] = [
                    'old' => null,
                    'new' => $value,
                ];
            }
            return $changes;
        }

        // For updates, compare old and new values
        if ($this->event === 'updated') {
            foreach ($this->new_values as $key => $value) {
                // If key doesn't exist in old values or values differ
                if (!array_key_exists($key, $this->old_values) || $this->old_values[$key] !== $value) {
                    $changes[$key] = [
                        'old' => $this->old_values[$key] ?? null,
                        'new' => $value,
                    ];
                }
            }
            return $changes;
        }

        // For deletions, all old values are changes
        if ($this->event === 'deleted') {
            foreach ($this->old_values as $key => $value) {
                $changes[$key] = [
                    'old' => $value,
                    'new' => null,
                ];
            }
            return $changes;
        }

        return $changes;
    }
}
