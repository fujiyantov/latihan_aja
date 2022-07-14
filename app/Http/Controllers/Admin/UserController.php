<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = User::where('id', '!=', 1)->latest()->get();

            /**
             * old
             * <a class="btn btn-primary btn-xs" href="' . route('user.edit', $item->id) . '"><i class="fas fa-edit"></i> &nbsp; Ubah</a>
             */
            return Datatables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <a class="btn btn-primary btn-xs" data-bs-toggle="modal" data-bs-target="#updateModal' . $item->id . '">
                            <i class="fas fa-edit"></i> &nbsp; Edit
                        </a>
                        <form action="' . route('user.destroy', $item->id) . '" method="POST" onsubmit="return confirm('."'Anda akan menghapus item ini secara permanen dari situs anda?'".')">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="btn btn-danger btn-xs">
                                <i class="far fa-trash-alt"></i> &nbsp; Hapus
                            </button>
                        </form>
                    ';
                })
                ->editColumn('name', function ($item) {
                    return $item->profile ? 
                                '<div class="d-flex align-items-center">
                                    <div class="avatar me-2"><img class="avatar-img img-fluid" src="'. Storage::url($item->profile) .'" /></div>'.
                                    $item->name .'
                                </div>' 
                            : 
                                '<div class="d-flex align-items-center">
                                    <div class="avatar me-2"><img class="avatar-img img-fluid" src="https://ui-avatars.com/api/?name='.$item->name.'" /></div>'.
                                    $item->name .'
                                </div>';
                })
                ->editColumn('position', function ($item) {
                    return $item->position_id != null ? $item->position->name : '-';
                })
                ->addIndexColumn()
                ->removeColumn('id')
                ->rawColumns(['action','name'])
                ->make();
        }

        $positions = Position::all();
        $users = User::where('id', '!=', 1)->get();

        return view('pages.admin.user.index', [
            'positions' => $positions,
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('pages.admin.user.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'position_id' => 'required|numeric|min:1',
            'email' => 'required|email|unique:users',
            // 'password' => 'required|min:5|max:255',
        ]);

        // $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['password'] = Hash::make('password');
        $validatedData['role_id'] = 2;

        User::create($validatedData);

        return redirect()
                    ->route('user.index')
                    ->with('success', 'Sukses! Data Pengguna Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        return view('pages.admin.user.index',[
            'user' => $user
        ]);
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);

        return view('pages.admin.user.edit',[
            'item' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'position_id' => 'required|numeric|min:1',
        ]);

        $item = User::findOrFail($id);
        
        $item->update($validatedData);

        return redirect()
                ->route('user.index')
                ->with('success', 'Sukses! Data Pengguna telah diperbarui');
    }

    public function destroy($id)
    {
        $item = User::findorFail($id);

        Storage::delete($item->profile);

        $item->delete();

        return redirect()
                ->route('user.index')
                ->with('success', 'Sukses! Data Pengguna telah dihapus');
    }

    public function upload_profile(Request $request)
    {
        $validatedData = $request->validate([
            'profile' => 'required|image|file|max:1024',
        ]);

        $id = $request->id;
        $item = User::findOrFail($id);

        //dd($item);

        if($request->file('profile')){
            Storage::delete($item->profile);
            $item->profile = $request->file('profile')->store('assets/profile-images');
        }

        $item->save();

        return redirect()
                ->route('user.index')
                ->with('success', 'Sukses! Photo Pengguna telah diperbarui');
    }

    public function change_password()
    {
        return view('pages.admin.user.change-password');
    }

    
}
