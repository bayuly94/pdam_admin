<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolumeHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VolumeHistoryController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $histories = VolumeHistory::with(['customer', 'employee'])
            ->when(request('search'), function ($query) {
                $query->whereHas('customer', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('code', 'like', '%' . request('search') . '%');
                });
            })
            ->when($start_date != null && $end_date != null, function ($query) use ($start_date, $end_date) {
                $s1 = Carbon::parse($start_date)->format('Y-m-d');
                $s2 = Carbon::parse($end_date)->format('Y-m-d');
                
               
                $query->whereBetween('date', [
                    $s1 . ' 00:00:00',
                    $s2 . ' 23:59:59'
                ]);
            })
            ->latest()
            ->paginate(10);

        return view('admin.volume-histories.index', compact('histories'));
    }

    public function create()
    {
        // You can implement create functionality if needed
        return view('admin.volume-histories.create');
    }

    public function store(Request $request)
    {
        // You can implement store functionality if needed
    }

    public function show(VolumeHistory $volumeHistory)
    {
        
        return view('admin.volume-histories.show', compact('volumeHistory'));
    }

    public function edit(VolumeHistory $volumeHistory)
    {
        // You can implement edit functionality if needed
        return view('admin.volume-histories.edit', compact('volumeHistory'));
    }

    public function update(Request $request, VolumeHistory $volumeHistory)
    {
        // You can implement update functionality if needed
    }

    public function destroy(VolumeHistory $volumeHistory)
    {
        // You can implement delete functionality if needed
        $volumeHistory->delete();
        return redirect()->route('admin.volume-histories.index')
            ->with('success', 'Volume history deleted successfully');
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
