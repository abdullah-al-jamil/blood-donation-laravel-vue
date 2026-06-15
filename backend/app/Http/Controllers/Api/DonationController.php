<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Models\BloodInventory;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->role === 'admin') {
            $donations = Donation::with(['user', 'donationCenter', 'appointment'])
                ->paginate(20);
        } else {
            $donations = Donation::where('user_id', $request->user()->id)
                ->with(['user', 'donationCenter', 'appointment'])
                ->paginate(20);
        }

        return response()->json(DonationResource::collection($donations));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'center_id' => ['required', 'integer', 'exists:donation_centers,id'],
            'appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
            'donation_date' => ['required', 'date'],
            'bags' => ['required', 'integer', 'min:1'],
            'blood_type' => ['required', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $donation = Donation::create($validated);

        BloodInventory::create([
            'donation_id' => $donation->id,
            'blood_type' => $validated['blood_type'],
            'bags' => $validated['bags'],
            'expiry_date' => now()->addDays(42),
            'status' => 'available',
        ]);

        $donation->user()->update(['last_donation_at' => now()]);

        if ($request->appointment_id) {
            $donation->appointment()->update(['status' => 'completed']);
        }

        $donation->load(['user', 'donationCenter', 'appointment', 'bloodInventory']);

        return response()->json(new DonationResource($donation), 201);
    }

    public function show(Request $request, Donation $donation): JsonResponse
    {
        if ($request->user()->role !== 'admin' && $donation->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $donation->load(['user', 'donationCenter', 'appointment', 'bloodInventory']);

        return response()->json(new DonationResource($donation));
    }

    public function update(Request $request, Donation $donation): JsonResponse
    {
        $validated = $request->validate([
            'center_id' => ['sometimes', 'integer', 'exists:donation_centers,id'],
            'donation_date' => ['sometimes', 'date'],
            'bags' => ['sometimes', 'integer', 'min:1'],
            'blood_type' => ['sometimes', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $donation->update($validated);
        $donation->load(['user', 'donationCenter', 'appointment', 'bloodInventory']);

        return response()->json(new DonationResource($donation));
    }

    public function destroy(Donation $donation): JsonResponse
    {
        $donation->bloodInventory()->delete();
        $donation->delete();

        return response()->json(['message' => 'Donation deleted.']);
    }
}
