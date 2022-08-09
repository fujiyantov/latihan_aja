<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        $collections = User::whereIn('role_id', [2, 3, 4, 5])->get();
        return view('pages.admin.finance.index', ['collections' => $collections]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $createdAllowed = [13]; // keungan
        if (!in_array($user->position->id, $createdAllowed)) {
            abort(403);
        }
        
        DB::beginTransaction();
        $validatedData = $request->validate([
            'lembaga_id' => 'required|numeric',
            'dana_received' => 'numeric|min:0',
            // 'dana_used' => 'numeric|min:0',
        ]);


        $letter = User::findOrFail($request->input('lembaga_id'));
        $dana_received = $request->input('dana_received');
        
        if (isset($dana_received) && $request->input('dana_received') != NULL) {
            $letter->dana_received = $request->input('dana_received');
        }

        $dana_used = $request->input('dana_used');
        if (isset($dana_used) && $request->input('dana_used') != NULL) {
            $letter->dana_used = $request->input('dana_used');
        }

        if ($dana_used > $dana_received) {
            return redirect()
                ->route('finances.index')
                ->with('success', 'Data yang digunakan lebih besar');
        }

        if (isset($dana_received) && isset($dana_used) && $dana_used != NULL) {

            $hitung  = $request->input('dana_received') - $request->input('dana_used');

            if ($hitung < 1) {
                $hitung = 0;
            }


            $letter->dana_sisa = $hitung;
        }

        $letter->save();
        
        DB::commit();

        return redirect()
            ->route('finances.index')
            ->with('success', 'Sukses! Data berhasil diupdate');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return [
            "dana_received" => $user->dana_received,
            "dana_used" => $user->dana_used,
            "dana_sisa" => $user->dana_sisa,
        ];
    }
}
