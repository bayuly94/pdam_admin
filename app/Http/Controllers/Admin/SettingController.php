<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    //


    public function form()
    {
        return view('admin.settings.form');
    }

    public function update(Request $request)
    {
        $request->validate([
            'about' => '',
        ]);

        Setting::where('code', Setting::CODE_ABOUT)->update([
            'value' => $request->about,
        ]);


        return redirect()->route('admin.settings.form')->with('success', 'Settings updated successfully');
    }
}
