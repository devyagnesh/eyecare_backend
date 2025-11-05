<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EyeExamination extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'store_id',
        'exam_date',
        'chief_complaint',
        'old_rx_date',
        'od_va_unaided',
        'os_va_unaided',
        'od_sphere',
        'od_cylinder',
        'od_axis',
        'os_sphere',
        'os_cylinder',
        'os_axis',
        'add_power',
        'pd_distance',
        'pd_near',
        'od_bcva',
        'os_bcva',
        'iop_od',
        'iop_os',
        'fundus_notes',
        'diagnosis',
        'management_plan',
        'next_recall_date',
        'pdf_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exam_date' => 'date',
        'old_rx_date' => 'date',
        'next_recall_date' => 'date',
        'od_sphere' => 'decimal:2',
        'od_cylinder' => 'decimal:2',
        'os_sphere' => 'decimal:2',
        'os_cylinder' => 'decimal:2',
        'add_power' => 'decimal:2',
        'pd_distance' => 'decimal:2',
        'pd_near' => 'decimal:2',
        'od_axis' => 'integer',
        'os_axis' => 'integer',
        'iop_od' => 'integer',
        'iop_os' => 'integer',
    ];

    /**
     * Get the customer that owns the eye examination.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the store that owns the eye examination.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
