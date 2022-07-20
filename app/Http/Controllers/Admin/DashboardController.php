<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Letter;

class DashboardController extends Controller
{
    public function index()
    {
        $masuk = Letter::get()->count();
        $keluar = Letter::where('status', 1)->get()->count();

        return view('pages.admin.dashboard',[
            'masuk' => $masuk,
            'keluar' => $keluar
        ]);
    }
}
