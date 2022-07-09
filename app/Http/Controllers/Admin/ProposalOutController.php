<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Letter;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\LetterHistories;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProposalOutController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Letter::latest()->get();

            return Datatables::of($query)
                ->addColumn('action', function ($item) {
                    if(Auth::user()->role_id == 2) {
                        return '
                            <a class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#updateModal' . $item->id . '">
                                <i class="fas fa-edit"></i> &nbsp; Ubah
                            </a>
                            <form action="' . route('proposal-keluar.destroy', $item->id) . '" method="POST" onsubmit="return confirm(' . "'Anda akan menghapus item ini dari situs anda?'" . ')">
                                ' . method_field('delete') . csrf_field() . '
                                <button class="btn btn-danger btn-xs">
                                    <i class="far fa-trash-alt"></i> &nbsp; Hapus
                                </button>
                            </form>
                        ';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('proposal', function ($item) {
                    $letterUrl = Storage::url('/assets/letter-file/' . $item->letter_file);
                    return '
                    <a class="btn btn-primary btn-xs" target="_blank" href="' . $letterUrl . '" ">
                        Lihat disini
                    </a>
                    ';
                })
                ->addColumn('status', function ($item) {
                    return $item->status == 1 ? 'Sudah di validasi' : 'Belum di validasi';
                })
                ->addColumn('tanggal', function ($item) {
                    return Carbon::parse($item->tanggal)->format("d/m/Y");
                })
                ->addIndexColumn()
                ->removeColumn('id')
                ->rawColumns(['action', 'proposal', 'status', 'tanggal'])
                ->make();
        }
        $letter = Letter::all();

        return view('pages.admin.out.index', [
            'letter' => $letter
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $validatedData = $request->validate([
            'letter_no' => 'required',
            'title' => 'required',
            'date' => 'required',
            'letter_file' => 'required|mimes:pdf,png,jpg,jpeg|file',
        ]);

        if ($request->file('letter_file')) {
            $validatedData['letter_file'] = $request->file('letter_file')->getClientOriginalName();
            $request->file('letter_file')->storeAs('assets/letter-file/', $validatedData['letter_file']);
        }

        $validatedData['member_id'] = Auth::user()->id;
        $validatedData['status'] = 0;


        $letter = Letter::create($validatedData);
        LetterHistories::create([
            'letter_id' => $letter->id,
            'member_id' => $letter->member_id,
            'description' => "Proposal dalam pengajuan, harap memunggu validasi",
        ]);


        DB::commit();

        return redirect()
            ->route('proposal-keluar.index')
            ->with('success', 'Sukses! 1 Data Berhasil Disimpan');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $validatedData = $request->validate([
            'letter_no' => 'required',
            'title' => 'required',
            'date' => 'required',
        ]);

        $letter = Letter::findOrFail($id);

        if ($request->file('letter_file')) {
            $validatedData['letter_file'] = $request->file('letter_file')->getClientOriginalName();
            $request->file('letter_file')->storeAs('assets/letter-file/', $validatedData['letter_file']);

            $letter->letter_file = $validatedData['letter_file'];
        }

        $letter->letter_no = $request->input('letter_no');
        $letter->title = $request->input('title');
        $letter->date = $request->input('date');
        $letter->save();

        DB::commit();

        return redirect()
            ->route('proposal-keluar.index')
            ->with('success', 'Sukses! 1 Data Berhasil Diubah');
    }

    public function destroy($id)
    {
        $item = Letter::findorFail($id);

        $item->delete();

        return redirect()
            ->route('proposal-keluar.index')
            ->with('success', 'Sukses! 1 Data Berhasil Dihapus');
    }
}
