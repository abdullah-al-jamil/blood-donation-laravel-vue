<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_type',
        'bags',
        'expiry_date',
        'status',
        'donation_id',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'bags' => 'integer',
        ];
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }
}
