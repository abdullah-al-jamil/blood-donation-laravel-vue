<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'blood_type' => $this->blood_type,
            'phone' => $this->phone,
            'dob' => $this->dob?->format('Y-m-d'),
            'address' => $this->address,
            'is_eligible' => $this->is_eligible,
            'last_donation_at' => $this->last_donation_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
