<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTermsAcceptance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_terms_acceptance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'terms_and_condition_id',
        'ip_address',
        'user_agent',
        'accepted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    /**
     * Get the user that accepted the terms.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the terms and conditions that were accepted.
     */
    public function termsAndCondition(): BelongsTo
    {
        return $this->belongsTo(TermsAndCondition::class, 'terms_and_condition_id');
    }
}

