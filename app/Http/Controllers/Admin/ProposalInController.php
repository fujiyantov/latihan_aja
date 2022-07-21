<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Role;

use App\Models\User;
use App\Models\Letter;
use App\Models\Member;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\LetterHistories;
use App\Models\LetterSubmission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProposalInController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /**
         * BEM = [2,5]
         * Kaprodi = [2,5,6]
         * Kaprodi = [2,5,6,8]
         * Pembina = [3,7]
         * Dst...
         */
        // himpunan ke bem, bem dekan verifikasi sekre, admin user password proposal, pdf
        if (request()->ajax()) {

            if (in_array($user->role_id, [2, 3, 4])) { // Pengajuan Awal

                $query = Letter::latest()
                    ->whereHas('user', function ($sub) use ($user) {
                        $sub->where('role_id', $user->role_id);
                    })
                    ->get();
            } else if ($user->role_id == 1) {

                $query = Letter::latest()->get();
            } else if ($user->role_id == 5) { // BEM

                $query = Letter::latest()
                    ->whereHas('user', function ($res) use ($user) {
                        $res->whereIn('role_id', [2, 5]);
                    })
                    ->get();
            } else { // Verifikasi or Validasi

                $query = Letter::latest()
                    ->whereHas('submission', function ($res) use ($user) {
                        $res->where('next_approval_by', $user->role_id);
                    })
                    ->get();
            }

            return Datatables::of($query)
                ->addColumn('keterangan', function ($item) {
                    return $item->user->name;
                })
                ->addColumn('action', function ($item) {
                    $verifikasi = '';
                    $wording = Auth::user()->role_id == 9 ? 'validasi' : 'verifikasi';

                    $verified = false;
                    $checkVerified = $item->submission->where('next_approval_by', Auth::user()->role_id)->first();
                    if (isset($checkVerified) && $checkVerified->status == 1) {
                        $verified = true;
                    }

                    $userLogin = Auth::user();
                    if ($userLogin->role_id == 5 && $item->user->role_id == 5) {
                        $verifikasi = '';
                    } else if (
                        $verified == true
                    ) {
                        $verifikasi = 'Verified';
                    } else if ($userLogin->role_id >= 5) {
                        $verifikasi = '
                            <form action="' . route('validasi', $item->id) . '" method="POST" onsubmit="return confirm(' . "'Anda akan mem'.$wording.' proposal ini?'" . ')">
                                    ' . csrf_field() . '
                                    <button class="btn btn-success btn-xs">
                                        <i class="fa fa-pen"></i> &nbsp; ' . $wording . '
                                    </button>
                                </form>
                            ';
                    }


                    $deleteBtn = '<form action="' . route('proposal-keluar.destroy', $item->id) . '" method="POST" onsubmit="return confirm(' . "'Anda akan menghapus item ini dari situs anda?'" . ')">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="btn btn-danger btn-xs">
                                <i class="far fa-trash-alt"></i> &nbsp; Hapus
                            </button>
                        </form>';

                    if ($item->status == 1) {
                        $deleteBtn = '';
                    }

                    return $verifikasi . '
                    ' . $deleteBtn . '
                    ';
                })
                ->addColumn('validasi', function ($item) {

                    $wording = Auth::user()->role_id == 9 ? 'validasi' : 'verifikasi';

                    return '
                    <form action="' . route('validasi', $item->id) . '" method="POST" onsubmit="return confirm(' . "'Anda akan mem'.$wording.' proposal ini?'" . ')">
                            ' . csrf_field() . '
                            <button class="btn btn-success btn-xs">
                                <i class="fa fa-pen"></i> &nbsp; ' . $wording . '
                            </button>
                        </form>
                    ';
                })
                ->addColumn('disposisi', function ($item) {
                    $disposisi = '<a class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#updateModalCatatan' . $item->id . '"><i class="fas fa-eye"></i> &nbsp; Lihat Catatan</a>';

                    return $disposisi . '
                            <a class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#updateModalDisposisi' . $item->id . '">
                                <i class="fa fa-comment"></i>
                            </a>
                        ';
                    /* // if (Auth::user()->role_id == 3) {
                    // <a class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#updateModalCatatan' . $item->id . '">
                    //     <i class="fas fa-eye"></i> &nbsp; Lihat Catatan
                    // </a>
                    return '
                            <a class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#updateModal' . $item->id . '">
                                Disposisi
                            </a>
                        ';
                    // } else {
                    //     return '-';
                    // } */
                })
                ->addColumn('proposal', function ($item) {
                    $letterUrl = Storage::url('/assets/letter-file/' . $item->letter_file);
                    return '
                        <a class="btn btn-primary btn-xs" target="_blank" href="' . $letterUrl . '">
                            <i class="fa fa-file"></i> &nbsp; Lihat Proposal
                        </a>
                    ';
                })
                ->addColumn('tanggal', function ($item) {
                    return Carbon::parse($item->date)->format("d/m/Y");
                })
                ->addIndexColumn()
                ->removeColumn('id')
                ->rawColumns(['action', 'proposal', 'validasi', 'tanggal', 'disposisi', 'keterangan'])
                ->make();
        }
        $letter = Letter::all();
        $position = User::where('id', '!=', 1)->get();

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
        /* $item = Letter::findorFail($id);
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
        } */

        $user = Auth::User();
        $letter = Letter::findorFail($id);

        // IF Letter created by BEM

        if ($letter->user->role_id == 5 && $user->position->id < 5) {

            $nextApprovalBy = 9;
            $validatedData['next_approval_by'] = 9;

        } else {

            switch ($user->position->id) {

                case '5': // BEM
                    $nextApprovalBy = 6;
                    $validatedData['next_approval_by'] = 6;
                    break;

                case '6': // Kaprodi
                    $nextApprovalBy = 8;
                    $validatedData['next_approval_by'] = 8;
                    break;

                case '7': // Pembina
                    $nextApprovalBy = 8;
                    $validatedData['next_approval_by'] = 8;
                    break;

                case '8': // BKA
                    $nextApprovalBy = 9;
                    $validatedData['next_approval_by'] = 9;
                    break;

                case '9': // Sekretariat
                    $nextApprovalBy = 10;
                    $validatedData['next_approval_by'] = 10;
                    break;

                case '10': // Dekan
                    $nextApprovalBy = 11;
                    $validatedData['next_approval_by'] = 11;
                    break;

                case '11': // Wadek II
                    $nextApprovalBy = 12;
                    $validatedData['next_approval_by'] = 12;
                    break;

                case '12': // Wadek I
                    $nextApprovalBy = 13;
                    $validatedData['next_approval_by'] = 13;
                    break;

                case '13': // Bag Keuangan

                    // DONE
                    $nextApprovalBy = 14;
                    $validatedData['next_approval_by'] = 14;
                    break;

                default:
                    return;
                    break;
            }
        }

        DB::beginTransaction();

        $updateVaidasi = LetterSubmission::where([
            ['letter_id', $id],
            ['next_approval_by', $user->position->id],
        ])
            ->latest()
            ->first();

        $updateVaidasi->status = 1;
        $updateVaidasi->save();

        $letterUpdate = Letter::where('id', $id)->first();
        $letterUpdate->status = 1;
        $letterUpdate->save();

        /**
         * created submission
         */
        LetterSubmission::create([
            'letter_id' => $letter->id,
            'created_by' => $user->id,
            'next_approval_by' => $nextApprovalBy,
        ]);

        DB::commit();

        return redirect()
            ->route('proposal-masuk.index')
            ->with('success', 'Sukses!');
    }

    public function disposisi(Request $request, $id)
    {

        $validatedData = $request->validate([
            'member_id' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $item = Letter::findorFail($id);

        // jika sudah divalidasi
        LetterHistories::create([
            'letter_id' => $item->id,
            'member_id' => $request->input('member_id'),
            'description' => $request->input('description'),
        ]);

        return redirect()
            ->route('proposal-masuk.index')
            ->with('success', 'Sukses! Proposal berhasil disposisikan');
    }
}
