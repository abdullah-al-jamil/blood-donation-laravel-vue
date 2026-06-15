<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloodRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'max:255'],
            'blood_type' => ['required', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'bags_needed' => ['required', 'integer', 'min:1'],
            'hospital' => ['required', 'string', 'max:255'],
            'urgency' => ['nullable', 'string', 'in:low,medium,high,critical'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
