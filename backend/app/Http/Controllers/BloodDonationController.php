<?php

namespace App\Http\Controllers;

use App\Models\BloodDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodDonation::with('donor');

        if ($request->has('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->user()->isDonor()) {
            $query->where('donor_id', $request->user()->id);
        }

        return response()->json($query->orderBy('donation_date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'blood_type' => 'required|string',
            'quantity_ml' => 'required|integer|min:100|max:500',
            'donation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $donation = BloodDonation::create([
            'donor_id' => $request->user()->id,
            'blood_type' => $request->blood_type,
            'quantity_ml' => $request->quantity_ml,
            'donation_date' => $request->donation_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return response()->json($donation->load('donor'), 201);
    }

    public function show(BloodDonation $bloodDonation)
    {
        return response()->json($bloodDonation->load('donor'));
    }

    public function update(Request $request, BloodDonation $bloodDonation)
    {
        $request->validate([
            'status' => 'sometimes|in:pending,completed,rejected',
            'notes' => 'nullable|string',
        ]);

        $bloodDonation->update($request->only(['status', 'notes']));

        return response()->json($bloodDonation->load('donor'));
    }

    public function destroy(BloodDonation $bloodDonation)
    {
        $bloodDonation->delete();
        return response()->json(['message' => 'Donation deleted successfully']);
    }
}
