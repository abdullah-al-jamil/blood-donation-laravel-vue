<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'center_id',
        'appointment_id',
        'donation_date',
        'bags',
        'blood_type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'donation_date' => 'date',
            'bags' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donationCenter(): BelongsTo
    {
        return $this->belongsTo(DonationCenter::class, 'center_id');
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function bloodInventory(): HasOne
    {
        return $this->hasOne(BloodInventory::class);
    }
}
