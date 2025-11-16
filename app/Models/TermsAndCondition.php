<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermsAndCondition extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'version',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created the terms.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the terms.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the users who accepted these terms.
     */
    public function acceptedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_terms_acceptance')
            ->withPivot('ip_address', 'user_agent', 'accepted_at')
            ->withTimestamps();
    }

    /**
     * Get acceptance records.
     */
    public function acceptances(): HasMany
    {
        return $this->hasMany(UserTermsAcceptance::class, 'terms_and_condition_id');
    }

    /**
     * Get the table name for the model.
     *
     * @return string
     */
    public function getTable()
    {
        return 'terms_and_conditions';
    }

    /**
     * Scope a query to only include active terms.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the latest active terms.
     */
    public static function getLatest()
    {
        return static::active()->latest()->first();
    }
}
