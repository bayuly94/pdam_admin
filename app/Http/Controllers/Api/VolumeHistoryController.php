<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVolumeHistoryRequest;
use App\Models\Customer;
use App\Models\VolumeHistory;
use Illuminate\Http\JsonResponse;

class VolumeHistoryController extends Controller
{
    public function store(StoreVolumeHistoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['employee_id'] = $request->user()->id;
        $validated['date'] = now(); // or use $validated['date'] if you want to use the provided date


        $customer = Customer::find($request->customer_id);

        $validated['before'] = $customer->volume_total();
        $validated['after'] = $validated['before'] + $validated['volume'];

        $volumeHistory = VolumeHistory::create($validated);

        return response()->json([
            'message' => 'Volume history created successfully',
            'data' => $volumeHistory->load('customer', 'employee')
        ], 201);
    }

    public function index()
    {
        $volumeHistories = VolumeHistory::with(['customer', 'employee'])
            ->latest()
            ->paginate(10);

        return response()->json($volumeHistories);
    }
}