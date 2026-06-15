<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloodInventoryRequest;
use App\Http\Resources\BloodInventoryResource;
use App\Models\BloodInventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BloodInventoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = BloodInventory::with('donation');

        if ($request->has('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $inventory = $query->paginate(20);

        return response()->json(BloodInventoryResource::collection($inventory));
    }

    public function summary(): JsonResponse
    {
        $totalByType = BloodInventory::where('status', 'available')
            ->selectRaw('blood_type, SUM(bags) as total_bags')
            ->groupBy('blood_type')
            ->pluck('total_bags', 'blood_type');

        $statusCounts = BloodInventory::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            'total_bags_by_type' => $totalByType,
            'status_counts' => $statusCounts,
            'grand_total_bags' => BloodInventory::where('status', 'available')->sum('bags'),
        ]);
    }

    public function store(StoreBloodInventoryRequest $request): JsonResponse
    {
        $inventory = BloodInventory::create($request->validated());

        return response()->json(new BloodInventoryResource($inventory), 201);
    }

    public function update(StoreBloodInventoryRequest $request, BloodInventory $inventory): JsonResponse
    {
        $inventory->update($request->validated());

        return response()->json(new BloodInventoryResource($inventory->fresh()));
    }

    public function destroy(BloodInventory $inventory): JsonResponse
    {
        $inventory->delete();

        return response()->json(['message' => 'Inventory entry deleted.']);
    }
}
