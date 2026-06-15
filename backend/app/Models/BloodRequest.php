<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'blood_type',
        'bags_needed',
        'hospital',
        'urgency',
        'status',
        'requested_by',
        'fulfilled_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'bags_needed' => 'integer',
            'fulfilled_at' => 'datetime',
        ];
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
