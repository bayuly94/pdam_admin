<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolumeHistory;
use Illuminate\Http\Request;

class VolumeHistoryController extends Controller
{
    public function index()
    {
        $histories = VolumeHistory::with(['customer', 'employee'])
            ->when(request('search'), function ($query) {
                $query->whereHas('customer', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('code', 'like', '%' . request('search') . '%');
                });
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
}
