<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Letter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $masuk = Letter::get()->count();
        $keluar = Letter::where('status', 1)->get()->count();

        // $tdd = User::where('id');
        $tdy = 0;
        $tsd = 0;

        return view('pages.admin.dashboard',[
            'masuk' => $masuk,
            'keluar' => $keluar,
            'tdd' => Auth::user()->dana_received,
            'tdy' => Auth::user()->dana_used,
            'tsd' => Auth::user()->dana_sisa,
        ]);
    }
}
