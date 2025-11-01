<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'name',
        'email',
        'phone_number',
        'address',
    ];

    /**
     * Get the store that owns the customer.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
