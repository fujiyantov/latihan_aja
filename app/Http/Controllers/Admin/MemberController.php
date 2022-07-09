<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Position;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Member::latest()->get();

            return Datatables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <a class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#updateModal' . $item->id . '">
                            <i class="fas fa-edit"></i> &nbsp; Edit
                        </a>
                        <form action="' . route('anggota.destroy', $item->id) . '" method="POST">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="btn btn-danger btn-xs">
                                <i class="far fa-trash-alt"></i> &nbsp; Hapus
                            </button>
                        </form>
                    ';
                })
                ->addColumn('tanggal', function ($item) {
                    return Carbon::parse($item->tanggal)->format("d/m/Y");
                })
                ->addColumn('position', function ($item) {
                    return $item->position->name;
                })
                ->addIndexColumn()
                ->removeColumn('id')
                ->rawColumns(['action', 'tanggal', 'position'])
                ->make();
        }

        $member = Member::all();
        $positions = Position::all();

        return view('pages.admin.anggota.index', [
            'member' => $member,
            'positions' => $positions,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string',
            'position_id' => 'required|numeric',
        ]);

        Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'position_id' => $request->position_id,
        ]);

        return redirect()
            ->route('anggota.index')
            ->with('success', 'Sukses! 1 Data Berhasil Disimpan');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string',
            'position_id' => 'required|numeric',
        ]);

        Member::where('id', $id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'position_id' => $request->position_id,
            ]);

        return redirect()
            ->route('anggota.index')
            ->with('success', 'Sukses! 1 Data telah diperbarui');
    }

    public function destroy($id)
    {
        $item = Member::findorFail($id);

        $item->delete();

        return redirect()
            ->route('anggota.index')
            ->with('success', 'Sukses! 1 Data Berhasil Dihapus');
    }
}
