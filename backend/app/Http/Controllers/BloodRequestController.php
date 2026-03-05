<?php

namespace App\Http\Controllers;

use App\Models\BloodDonation;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodRequest::with(['user', 'donation']);

        if ($request->has('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->user()->isRecipient()) {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'blood_type' => 'required|string',
            'quantity_ml' => 'required|integer|min:100|max:1000',
            'reason' => 'nullable|string',
            'hospital' => 'nullable|string',
            'required_date' => 'nullable|date',
        ]);

        $bloodRequest = BloodRequest::create([
            'user_id' => $request->user()->id,
            'blood_type' => $request->blood_type,
            'quantity_ml' => $request->quantity_ml,
            'reason' => $request->reason,
            'hospital' => $request->hospital,
            'required_date' => $request->required_date,
            'status' => 'pending',
        ]);

        return response()->json($bloodRequest->load(['user', 'donation']), 201);
    }

    public function show(BloodRequest $bloodRequest)
    {
        return response()->json($bloodRequest->load(['user', 'donation']));
    }

    public function update(Request $request, BloodRequest $bloodRequest)
    {
        $request->validate([
            'status' => 'sometimes|in:pending,approved,fulfilled,rejected',
            'donation_id' => 'nullable|exists:blood_donations,id',
        ]);

        $bloodRequest->update($request->only(['status', 'donation_id']));

        if ($request->status === 'fulfilled' && $bloodRequest->donation) {
            $bloodRequest->donation->update(['status' => 'fulfilled']);
        }

        return response()->json($bloodRequest->load(['user', 'donation']));
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        $bloodRequest->delete();
        return response()->json(['message' => 'Request deleted successfully']);
    }

    public function availableBlood(Request $request)
    {
        $query = BloodDonation::where('status', 'completed')
            ->where('blood_type', $request->blood_type)
            ->with('donor');

        return response()->json($query->get());
    }
}
