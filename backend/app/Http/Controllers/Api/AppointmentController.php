<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->role === 'admin' && $request->has('user_id')) {
            $appointments = Appointment::where('user_id', $request->user_id)
                ->with(['user', 'donationCenter'])
                ->paginate(20);
        } else {
            $appointments = Appointment::where('user_id', $request->user()->id)
                ->with(['user', 'donationCenter'])
                ->paginate(20);
        }

        return response()->json(AppointmentResource::collection($appointments));
    }

    public function adminIndex(Request $request): JsonResponse
    {
        $appointments = Appointment::with(['user', 'donationCenter'])
            ->paginate(20);

        return response()->json(AppointmentResource::collection($appointments));
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        $appointment = Appointment::create([
            'user_id' => $request->user()->id,
            'center_id' => $request->center_id,
            'appointment_date' => $request->appointment_date,
            'notes' => $request->notes,
        ]);

        $appointment->load(['user', 'donationCenter']);

        return response()->json(new AppointmentResource($appointment), 201);
    }

    public function show(Request $request, Appointment $appointment): JsonResponse
    {
        if ($request->user()->role !== 'admin' && $appointment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $appointment->load(['user', 'donationCenter']);

        return response()->json(new AppointmentResource($appointment));
    }

    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            if ($appointment->user_id !== $request->user()->id || $appointment->status !== 'scheduled') {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        }

        $validated = $request->validate([
            'center_id' => ['sometimes', 'integer', 'exists:donation_centers,id'],
            'appointment_date' => ['sometimes', 'date', 'after:now'],
            'status' => ['sometimes', 'string', 'in:scheduled,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $appointment->update($validated);
        $appointment->load(['user', 'donationCenter']);

        return response()->json(new AppointmentResource($appointment));
    }

    public function destroy(Request $request, Appointment $appointment): JsonResponse
    {
        if ($appointment->user_id !== $request->user()->id || $appointment->status !== 'scheduled') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Appointment cancelled.']);
    }
}
