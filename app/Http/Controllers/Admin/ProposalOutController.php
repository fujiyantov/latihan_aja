<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Role;

use App\Models\Letter;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\LetterHistories;
use App\Models\LetterSubmission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProposalOutController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (request()->ajax()) {
            $query = Letter::where('member_id', $user->id)
                ->latest()->get();

            return Datatables::of($query)
                ->addColumn('keterangan', function ($item) {
                    return $item->user->name;
                })
                ->addColumn('action', function ($item) {
                    if (Auth::user()->role_id == 2) {
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
                        <i class="fa fa-file"></i> &nbsp; Lihat Proposal
                    </a>
                    ';
                })
                ->addColumn('disposisi', function ($item) {
                    // if ($item->status == 1  && Auth::user()->role_id == 2) {
                    $disposisi = '';
                    // if (Auth::user()->role_id == 2) {
                    $disposisi = '<a class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#updateModalCatatan' . $item->id . '"><i class="fas fa-eye"></i> &nbsp; Lihat Catatan</a>';
                    // }
                    return $disposisi . '
                            <a class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#updateModalDisposisi' . $item->id . '">
                                <i class="fa fa-comment"></i>
                            </a>
                        ';
                    // } else {
                    //     return '-';
                    // }
                })
                ->addColumn('status', function ($item) {
                    $status = $item->submissionLatest->where('status', 1)->first();
                    
                    $str = 'Verifikasi';
                    if ($status->approval->role_id == 9) {
                        $str = 'Validasi';
                    }

                    return isset($status) ? $str . ' ' . $status->approval->position->name : 'Belum Vefifikasi';
                })
                ->addColumn('tanggal', function ($item) {
                    return Carbon::parse($item->tanggal)->format("d/m/Y");
                })
                ->addIndexColumn()
                ->removeColumn('id')
                ->rawColumns(['action', 'proposal', 'status', 'tanggal', "disposisi", 'keterangan'])
                ->make();
        }
        $letter = Letter::all();
        $position = User::where('id', '!=', 1)->get();

        return view('pages.admin.out.index', [
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

        $user = Auth::user();
        $createdAllowed = [2, 3, 4, 5]; // himpunan, ortom, komunitas, bem
        if (!in_array($user->position->id, $createdAllowed)) {
            return redirect()
                ->route('proposal-keluar.index')
                ->with('success', 'You dont have permission');
        }

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


        switch ($user->position->id) {
            case '2': // himpunan
                $nextApprovalBy = 5;
                $validatedData['next_approval_by'] = 5;
                break;

            case '3': // ortom
                $nextApprovalBy = 7;
                $validatedData['next_approval_by'] = 7;
                break;

            case '4': // komunitas
                $nextApprovalBy = 7;
                $validatedData['next_approval_by'] = 7;
                break;

            case '5': // bem
                $nextApprovalBy = 9;
                $validatedData['next_approval_by'] = 9;
                break;

            default:
                return;
                break;
        }

        $letter = Letter::create($validatedData);

        /**
         * created submission
         */

        LetterSubmission::create([
            'letter_id' => $letter->id,
            'created_by' => $user->id,
            'next_approval_by' => $nextApprovalBy,
        ]);

        /* LetterHistories::create([
            'letter_id' => $letter->id,
            'member_id' => $letter->member_id,
            'description' => "Proposal dalam pengajuan, harap memunggu validasi",
        ]); */


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
