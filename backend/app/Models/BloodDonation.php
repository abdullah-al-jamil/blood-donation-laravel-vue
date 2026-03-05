<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'blood_type',
        'quantity_ml',
        'donation_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'donation_date' => 'date',
        ];
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}
