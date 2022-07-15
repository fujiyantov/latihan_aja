<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Letter;

use App\Models\Member;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\LetterHistories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProposalInController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Letter::latest()->get();

            return Datatables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                    <form action="' . route('proposal-keluar.destroy', $item->id) . '" method="POST" onsubmit="return confirm(' . "'Anda akan menghapus item ini dari situs anda?'" . ')">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="btn btn-danger btn-xs">
                                <i class="far fa-trash-alt"></i> &nbsp; Hapus
                            </button>
                        </form>
                    ';
                })
                ->addColumn('validasi', function ($item) {
                    return '
                    <form action="' . route('validasi', $item->id) . '" method="POST" onsubmit="return confirm(' . "'Anda akan memvalidasi proposal ini?'" . ')">
                            ' . csrf_field() . '
                            <button class="btn btn-success btn-xs">
                                <i class="fa fa-pen"></i> &nbsp; Validasi
                            </button>
                        </form>
                    ';
                })
                ->addColumn('disposisi', function ($item) {
                    if (Auth::user()->role_id == 3) {
                        return '
                            <a class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#updateModalCatatan' . $item->id . '">
                                <i class="fas fa-eye"></i> &nbsp; Lihat Catatan
                            </a>
                            <a class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#updateModal' . $item->id . '">
                                <i class="fa fa-comment"></i>
                            </a>
                        ';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('proposal', function ($item) {
                    $letterUrl = Storage::url('/assets/letter-file/' . $item->letter_file);
                    return '
                        <a class="btn btn-primary btn-xs" target="_blank" href="' . $letterUrl . '">
                            <i class="fa fa-file"></i> &nbsp; Lihat Surat
                        </a>
                    ';
                })
                ->addColumn('tanggal', function ($item) {
                    return Carbon::parse($item->tanggal)->format("d/m/Y");
                })
                ->addIndexColumn()
                ->removeColumn('id')
                ->rawColumns(['action', 'proposal', 'validasi', 'tanggal', 'disposisi'])
                ->make();
        }
        $letter = Letter::all();
        $position = Position::all();

        return view('pages.admin.in.index', [
            'letter' => $letter,
            'position' => $position
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Department::create([
            'name' => $request->name
        ]);

        return redirect()
            ->route('department.index')
            ->with('success', 'Sukses! 1 Data Berhasil Disimpan');
    }

    public function show($id)
    {
        return view('pages.admin.in.show');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Department::where('id', $id)
            ->update([
                'name' => $request->name
            ]);

        return redirect()
            ->route('department.index')
            ->with('success', 'Sukses! 1 Data telah diperbarui');
    }

    public function destroy($id)
    {
        $item = Letter::findorFail($id);
        $item->delete();

        return redirect()
            ->route('proposal-masuk.index')
            ->with('success', 'Sukses! 1 Data Berhasil Dihapus');
    }

    public function validasi($id)
    {
        $item = Letter::findorFail($id);
        if ($item->status == 0) {
            $item->status = 1;
            $item->date_approval = date('Y-m-d H:i:s');
            $item->approval_by = Auth::user()->id;
            $item->save();

            LetterHistories::create([
                'letter_id' => $item->id,
                'member_id' => $item->member_id,
                'description' => "Proposal sudah divalidasi",
            ]);
        }

        return redirect()
            ->route('proposal-masuk.index')
            ->with('success', 'Sukses! 1 Proposal berhasil divalidasi');
    }

    public function disposisi(Request $request, $id)
    {

        $validatedData = $request->validate([
            'member_id' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $item = Letter::findorFail($id);

        // jika sudah divalidasi
        if ($item->status == 1) {
            LetterHistories::create([
                'letter_id' => $item->id,
                'member_id' => $request->input('member_id'),
                'description' => $request->input('description'),
            ]);

            return redirect()
                ->route('proposal-masuk.index')
                ->with('success', 'Sukses! Proposal berhasil disposisikan');
        } else {
            return redirect()
                ->route('proposal-masuk.index')
                ->with('success', 'Proposal belum tervalidasi');
        }
    }
}
