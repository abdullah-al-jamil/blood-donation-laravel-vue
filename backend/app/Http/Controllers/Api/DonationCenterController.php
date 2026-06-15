<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationCenterRequest;
use App\Http\Resources\DonationCenterResource;
use App\Models\DonationCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationCenterController extends Controller
{
    public function index(): JsonResponse
    {
        $centers = DonationCenter::paginate(20);

        return response()->json(DonationCenterResource::collection($centers));
    }

    public function show(DonationCenter $donationCenter): JsonResponse
    {
        return response()->json(new DonationCenterResource($donationCenter));
    }

    public function store(StoreDonationCenterRequest $request): JsonResponse
    {
        $center = DonationCenter::create($request->validated());

        return response()->json(new DonationCenterResource($center), 201);
    }

    public function update(StoreDonationCenterRequest $request, DonationCenter $donationCenter): JsonResponse
    {
        $donationCenter->update($request->validated());

        return response()->json(new DonationCenterResource($donationCenter->fresh()));
    }

    public function destroy(DonationCenter $donationCenter): JsonResponse
    {
        $donationCenter->delete();

        return response()->json(['message' => 'Donation center deleted.']);
    }
}
