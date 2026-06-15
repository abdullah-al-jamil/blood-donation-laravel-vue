<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BloodRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient_name,
            'blood_type' => $this->blood_type,
            'bags_needed' => $this->bags_needed,
            'hospital' => $this->hospital,
            'urgency' => $this->urgency,
            'status' => $this->status,
            'requested_by' => new UserResource($this->whenLoaded('requestedBy')),
            'fulfilled_at' => $this->fulfilled_at?->toISOString(),
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
