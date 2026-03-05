<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donation_id',
        'blood_type',
        'quantity_ml',
        'status',
        'reason',
        'hospital',
        'required_date',
    ];

    protected function casts(): array
    {
        return [
            'required_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(BloodDonation::class);
    }
}
