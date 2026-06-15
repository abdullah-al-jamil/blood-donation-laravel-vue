<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BloodInventoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'blood_type' => $this->blood_type,
            'bags' => $this->bags,
            'expiry_date' => $this->expiry_date?->format('Y-m-d'),
            'status' => $this->status,
            'donation' => new DonationResource($this->whenLoaded('donation')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
