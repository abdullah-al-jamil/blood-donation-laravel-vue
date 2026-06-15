<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodRequestRequest;
use App\Http\Resources\BloodRequestResource;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BloodRequest::with('requestedBy');

        if ($request->has('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $requests = $query->orderByRaw("CASE urgency WHEN 'critical' THEN 0 WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json(BloodRequestResource::collection($requests));
    }

    public function store(StoreBloodRequestRequest $request): JsonResponse
    {
        $bloodRequest = BloodRequest::create([
            'patient_name' => $request->patient_name,
            'blood_type' => $request->blood_type,
            'bags_needed' => $request->bags_needed,
            'hospital' => $request->hospital,
            'urgency' => $request->urgency ?? 'medium',
            'requested_by' => $request->user()?->id ?? 1,
            'notes' => $request->notes,
        ]);

        $bloodRequest->load('requestedBy');

        return response()->json(new BloodRequestResource($bloodRequest), 201);
    }

    public function show(BloodRequest $bloodRequest): JsonResponse
    {
        $bloodRequest->load('requestedBy');

        return response()->json(new BloodRequestResource($bloodRequest));
    }

    public function update(Request $request, BloodRequest $bloodRequest): JsonResponse
    {
        $validated = $request->validate([
            'patient_name' => ['sometimes', 'string', 'max:255'],
            'blood_type' => ['sometimes', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'bags_needed' => ['sometimes', 'integer', 'min:1'],
            'hospital' => ['sometimes', 'string', 'max:255'],
            'urgency' => ['sometimes', 'string', 'in:low,medium,high,critical'],
            'status' => ['sometimes', 'string', 'in:pending,fulfilled,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $bloodRequest->update($validated);
        $bloodRequest->load('requestedBy');

        return response()->json(new BloodRequestResource($bloodRequest));
    }

    public function fulfill(BloodRequest $bloodRequest): JsonResponse
    {
        if ($bloodRequest->status === 'fulfilled') {
            return response()->json(['message' => 'Request already fulfilled.'], 400);
        }

        $bloodRequest->update([
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);

        $bloodRequest->load('requestedBy');

        return response()->json(new BloodRequestResource($bloodRequest));
    }

    public function myRequests(Request $request): JsonResponse
    {
        $requests = BloodRequest::where('requested_by', $request->user()->id)
            ->with('requestedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json(BloodRequestResource::collection($requests));
    }

    public function destroy(BloodRequest $bloodRequest): JsonResponse
    {
        $bloodRequest->delete();

        return response()->json(['message' => 'Blood request deleted.']);
    }
}
