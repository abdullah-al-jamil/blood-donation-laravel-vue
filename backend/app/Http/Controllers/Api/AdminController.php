<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Appointment;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $totalDonors = User::where('role', 'donor')->count();
        $totalDonations = Donation::count();
        $totalBagsByType = BloodInventory::where('status', 'available')
            ->selectRaw('blood_type, SUM(bags) as total_bags')
            ->groupBy('blood_type')
            ->pluck('total_bags', 'blood_type');
        $pendingRequests = BloodRequest::where('status', 'pending')->count();
        $upcomingAppointments = Appointment::where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->count();
        $recentDonations = Donation::with(['user', 'donationCenter'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $lowStockAlerts = BloodInventory::where('status', 'available')
            ->where('bags', '<', 5)
            ->selectRaw('blood_type, SUM(bags) as total_bags')
            ->groupBy('blood_type')
            ->having('total_bags', '<', 5)
            ->get();

        return response()->json([
            'total_donors' => $totalDonors,
            'total_donations' => $totalDonations,
            'total_bags_by_type' => $totalBagsByType,
            'pending_requests' => $pendingRequests,
            'upcoming_appointments' => $upcomingAppointments,
            'recent_donations' => $recentDonations,
            'low_stock_alerts' => $lowStockAlerts,
        ]);
    }

    public function donors(Request $request): JsonResponse
    {
        $donors = User::where('role', 'donor')
            ->when($request->has('blood_type'), fn ($q) => $q->where('blood_type', $request->blood_type))
            ->when($request->has('is_eligible'), fn ($q) => $q->where('is_eligible', $request->boolean('is_eligible')))
            ->paginate(20);

        return response()->json(UserResource::collection($donors));
    }

    public function showDonor(User $user): JsonResponse
    {
        if ($user->role !== 'donor') {
            return response()->json(['message' => 'Not a donor.'], 404);
        }

        $user->load(['donations', 'appointments']);

        return response()->json(new UserResource($user));
    }

    public function toggleEligibility(User $user): JsonResponse
    {
        if ($user->role !== 'donor') {
            return response()->json(['message' => 'Not a donor.'], 404);
        }

        $user->update(['is_eligible' => !$user->is_eligible]);

        return response()->json(new UserResource($user->fresh()));
    }
}
