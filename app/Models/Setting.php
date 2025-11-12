<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Setting Model
 * 
 * Manages application settings as key-value pairs.
 * 
 * @package App\Models
 */
class Setting extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    /**
     * Get the value cast to its proper type.
     *
     * @return mixed
     */
    public function getCastedValue()
    {
        return match ($this->type) {
            'integer' => (int) $this->value,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($this->value, true),
            'float' => (float) $this->value,
            default => $this->value,
        };
    }

    /**
     * Set the value with proper type casting.
     *
     * @param mixed $value
     * @return void
     */
    public function setCastedValue($value): void
    {
        $this->value = match ($this->type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };
    }

    /**
     * Scope to filter by group.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope to get public settings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Get setting by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->getCastedValue();
    }

    /**
     * Set setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @return static
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): static
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->type = $type;
        $setting->group = $group;
        $setting->setCastedValue($value);
        $setting->save();
        
        return $setting;
    }
}

