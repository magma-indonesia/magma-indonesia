<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\User;
use App\Notifications\User as UserNotification;
use App\UserBidang as Bidang;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /**
     * Adding middleware for protecttion
     *
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('owner')->only(['edit','update']);
        $this->middleware('role:Super Admin')->only(['create','store','destroy','reset','resetPassword']);
    }

    /**
     * Uploading photo profile user
     *
     * @return boolean
     */
    protected function uploadPhoto($user,$photo,$filetype)
    {

        list($type, $photo) = explode(';', $photo);
        list(, $photo)      = explode(',', $photo);
        $photo = base64_decode($photo);
        $photoName = uniqid().$filetype;

        $uploadPhoto = Storage::disk('user')->put($photoName, $photo);

        if (Storage::disk('user')->exists(optional($user->photo)->filename))
        {
            Storage::disk('user')->delete($user->photo->filename);
            Storage::disk('user-thumb')->delete($user->photo->filename);
        }

        $url = Storage::disk('user')->get($photoName);

        $thumbnail = Image::make($url)->resize(76, 76)->stream();

        $thumbnail = Storage::disk('user-thumb')->put($photoName,$thumbnail);

        if ($uploadPhoto) {
            $success = $user->photo()->updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'filename' => $photoName
                ]
            );
        } else {
            return redirect()->back()->with('flash_message','Gagal upload photo User.');
        }

        return $success ? true : false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(request()->user()->load('bidang'));
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserCreate  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreate $request)
    {
        $user = User::create($request->toArray());

        if ($user)
        {
            return redirect()->route('chambers.users.index')
                    ->with('flash_message',$request->name.' berhasil ditambahkan.');
        }

        return redirect()->route('chambers.users.index')
                ->with('flash_message','User gagal ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->route('chambers.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::get();
        $bidangs = Bidang::whereIn('code',['mga','mgb','mgt','bpt','btu'])->get();

        return view('users.edit', compact('user', 'roles', 'bidangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'nip' => 'required|digits:18|unique:users,nip,'.$user->id,
            'bidang' => 'required|in:2,3,4,5,6',
            'phone' => 'nullable|digits_between:11,12',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|boolean'
        ],[
            'name.required' => 'Nama tidak boleh kosong',
            'nip.required' => 'NIP tidak boleh kosong',
            'bidang.required' => 'Bidang harus dipilih',
            'phone.required' => 'No HP harus diisi',
            'email.required' => 'Email tidak boleh kosong',
            'password' => 'Password tidak boleh kosong',
            'status.required' => 'Status harus dipilih',
            'name.string' => 'Nama harus berformat huruf',
            'nip.digits' => 'NIP maksimal memiliki 18 karakter numerik',
            'nip.unique' => 'NIP telah digunakan oleh orang lain',
            'bidang.in' => 'Bidang yang dipilih tidak terdafatr',
            'phone.digits' => 'No HP tidak boleh lebih dari 12 angka',
            'phone.unique' => 'No HP telah digunakan oleh orang lain',
            'email.email' => 'Alamat email tidak valid',
            'email.unique' => 'Alamat email telah digunakan oleh orang lain',
            'password.min' => 'Password Minimal 6 karakter',
            'password.confirmed' => 'Password Konfirmasi tidak sama',
            'status.boolean' => 'Tipe status tidak valid',
        ]);

        $user->bidang()->updateOrCreate([
            'user_id' => $user->id
        ],[
            'bidang_id' => $request->bidang
        ]);

        $user->fill(
            $request->except(['imagebase64'])
        )->save();

        !empty($request->filetype) ? $this->uploadPhoto($user,$request->imagebase64,$request->filetype) : true;

        if ($request->has('roles'))
        {
            $roles = $request->roles;
            isset($roles) ? $user->roles()->sync($roles) : $user->roles()->detach();
        }

        $user->notify(new UserNotification('update',$user));

        return redirect()->route('chambers.users.index')
            ->with('flash_message',
            "Data {$user->name} berhasil dirubah.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $nama = $user->name;
        if ($user->delete()) {
            $user->notify(new UserNotification('delete',$user));
        }

        $data = [
            'success' => 1,
            'message' => $nama.' berhasil dihapus.'
        ];

        return response()->json($data);
    }

    public function reset()
    {
        $users = User::orderBy('name')->get();
        return view('users.reset', compact('users'));
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'nip' => 'required|exists:users,nip',
            'password' => 'required|confirmed|min:6'
        ],[
            'password.required' => 'Password tidak boleh kosong',
            'password.confirmed' => 'Konfirmasi Password tidak sama',
            'password.min' => 'Panjang Password minimal 6 karakter',
        ]);

        $user = User::whereNip($request->nip)->first();
        $user->password = $request->password;
        $user->save();

        return redirect()->route('chambers.users.index')->with('flash_message','Password berhasil direset');
    }
}
