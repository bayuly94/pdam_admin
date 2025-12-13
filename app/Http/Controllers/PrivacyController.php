<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('web.privacy');
    }
}
