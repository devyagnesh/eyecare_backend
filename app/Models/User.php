<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_spam',
        'is_blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_spam' => 'boolean',
            'is_blocked' => 'boolean',
        ];
    }

    /**
     * Get the role that the user belongs to.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all permissions for the user through their role.
     */
    public function permissions()
    {
        return $this->role->permissions ?? collect();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permissionSlug);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->role && $this->role->slug === $roleSlug;
    }

    /**
     * Get the devices for the user.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    /**
     * Get the store for the user.
     */
    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    /**
     * Scope a query to only include spam users.
     */
    public function scopeSpam($query)
    {
        return $query->where('is_spam', true);
    }

    /**
     * Scope a query to only include non-spam users.
     */
    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    /**
     * Scope a query to only include blocked users.
     */
    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    /**
     * Scope a query to only include non-blocked users.
     */
    public function scopeNotBlocked($query)
    {
        return $query->where('is_blocked', false);
    }

    /**
     * Get the terms and conditions accepted by the user.
     */
    public function acceptedTerms(): BelongsToMany
    {
        return $this->belongsToMany(TermsAndCondition::class, 'user_terms_acceptance')
            ->withPivot('ip_address', 'user_agent', 'accepted_at')
            ->withTimestamps();
    }

    /**
     * Check if user has accepted the latest terms.
     *
     * @return bool
     */
    public function hasAcceptedLatestTerms(): bool
    {
        $latestTerms = TermsAndCondition::getLatest();
        
        if (!$latestTerms) {
            return false;
        }

        return $this->acceptedTerms()
            ->where('terms_and_conditions.id', $latestTerms->id)
            ->exists();
    }

    /**
     * Get the latest accepted terms by the user.
     */
    public function getLatestAcceptedTerms()
    {
        return $this->acceptedTerms()
            ->orderBy('user_terms_acceptance.accepted_at', 'desc')
            ->first();
    }
}
