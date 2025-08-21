<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'description',
        'type',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the typed value of the setting.
     */
    public function getTypedValueAttribute()
    {
        if ($this->value === null) {
            return null;
        }

        return match($this->type) {
            'boolean' => (bool) $this->value,
            'number' => (float) $this->value,
            'integer' => (int) $this->value,
            'json', 'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->typed_value;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, $value, string $group = null): self
    {
        $setting = static::firstOrNew(['key' => $key]);
        
        if ($setting->exists) {
            $valueType = $setting->type;
        } else {
            $setting->group = $group;
            $valueType = self::determineType($value);
            $setting->type = $valueType;
        }
        
        // Format the value according to its type
        if ($valueType === 'json' || $valueType === 'array') {
            $setting->value = json_encode($value);
        } elseif ($valueType === 'boolean') {
            $setting->value = $value ? '1' : '0';
        } else {
            $setting->value = (string) $value;
        }
        
        $setting->save();
        
        return $setting;
    }

    /**
     * Determine the type of a value.
     */
    protected static function determineType($value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'number',
            is_array($value) => 'json',
            default => 'text',
        };
    }
}
