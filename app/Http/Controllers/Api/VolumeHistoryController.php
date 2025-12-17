<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVolumeHistoryRequest;
use App\Http\Resources\VolumeResource;
use App\Models\Customer;
use App\Models\VolumeHistory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        if ($validated['photo']) {
            $photo = $request->file('photo');
            $photoName = time().'.'.$photo->getClientOriginalExtension();
            $photo->move(public_path('photos'), $photoName);
            $validated['photo'] = 'photos/'.$photoName;
        }


        // check duplicate data
        $is_exist = VolumeHistory::where([
            'code'  => $validated['code'],
        ])->count() > 0 ? true : false;

        if ($is_exist) {
            return response()->json([
                'message' => 'Volume history already exists',
            ], 200);
        }

        $volumeHistory = VolumeHistory::create($validated);

        return response()->json([
            'message' => 'Volume history created successfully',
            'data' => $volumeHistory->load('customer', 'employee'),
        ], 201);
    }

    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $volumeHistories = VolumeHistory::with(['customer', 'employee'])
            ->when($start_date != null && $end_date != null, function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('date', [
                        $start_date . ' 00:00:00',
                        $end_date . ' 23:59:59'
                    ]);
                })    
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => VolumeResource::collection($volumeHistories),
            'pagination' => [
                'total' => $volumeHistories->total(),
                'per_page' => $volumeHistories->perPage(),
                'current_page' => $volumeHistories->currentPage(),
                'last_page' => $volumeHistories->lastPage(),
                'from' => $volumeHistories->firstItem(),
                'to' => $volumeHistories->lastItem(),
            ],
        ]);
    }

    public function update($id, Request $request)
    {
        $history = VolumeHistory::find($id);

        $before = $history->customer->volume_total_before($id);
        $after = $before + $request->volume;

        $update = [
            'before' => $before,
            'after' => $after,
            'volume' => $request->volume,
        ];

        $history->update($update);

        return response()->json([
            'success' => true,
            'message' => 'Volume history updated successfully',
        ], 200);
    }

    public function delete($id, Request $request)
    {
        $history = VolumeHistory::find($id);
        $history->delete();

        return response()->json([
            'success' => true,
            'message' => 'Volume history updated successfully',
        ], 200);
    }


    public function export(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');
        
        $filename = 'volume_histories_' . $start_date . '_to_' . $end_date . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\VolumeHistoriesExport($start_date, $end_date),
            $filename
        );
    }
}
