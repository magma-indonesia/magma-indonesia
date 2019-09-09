<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\User;
use App\UserPhoto;
use App\UserAdministratif;
use App\UserBidangDesc as Bidang;
use App\Notifications\UserLogin;
use App\Notifications\User As UserNotification;
use App\Jobs\SendLoginNotification;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;

class UserController extends Controller
{
    use ThrottlesLogins;

    protected $maxAttempts = 2;

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

    public function username()
    {
        $username = filter_var($this->request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
        return $username;
    }


    /** 
     * Login Controller User
     * @param  \Illuminate\Http\Request  $request
     * @return View
    */
    protected function loginAttempt($request)
    {
        try {

            $this->request = $request;
            
            $credentials = [
                $this->username() => $request->username,
                'password' => $request->password,
                'status' => 1
            ];

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            if (Auth::attempt($credentials)) {
                Auth::user()->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => last($request->getClientIps())    
                ]);

                SendLoginNotification::dispatch('web',Auth::user())
                    ->delay(now()->addSeconds(3));
                
                $token = Auth::guard('api')->attempt($credentials);

                return redirect()->intended('/')
                        ->header('Authorization','Bearer '.$token);
            }

            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                $request->username => [trans('auth.failed')],
            ]);

        } 
        
        catch (JWTException $e) {
            throw ValidationException::withMessages([
                $request->username => [trans('auth.failed')],
            ]);
        }

    }

    public function showLoginForm()
    {
        return view('users.login');
    }

    public function login(Request $request)
    {

        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        return $this->loginAttempt($request);

    }

    public function logout(Request $request)
    {
        $logout = Auth::logout();
        $request->session()->flush();

        return redirect()->route('home');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $bidangs = Bidang::whereIn('code',['mga','mgb','mgt','bpt','btu'])->get();
        return view('users.create',compact('bidangs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'nip' => 'required|digits:18|unique:users,nip,NULL,id,deleted_at,NULL',
            'bidang' => 'required|in:2,3,4,5,6',
            'phone' => 'nullable|digits_between:10,12|unique:users,phone,NULL,id,deleted_at,NULL',
            'email' => 'required|string|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|boolean'
        ]);

        $input = $request->except(['bidang','imagebase64']);
     
        $user = User::create($input);

        $bidang = UserAdministratif::create([
            'user_id' => $user->id,
            'bidang_id' => $request->bidang
        ]);
        
        $uploadPhoto = !empty($request->filetype) ? $this->uploadPhoto($user, $request->imagebase64, $request->filetype) : true;

        if ($user AND $uploadPhoto)
        {
            $user->notify(new UserNotification('create',$user));
            return redirect()->route('chambers.users.index')->with('flash_message',$request->name.' berhasil ditambahkan.');
        }

        return redirect()->route('chambers.users.index')->with('flash_message','User gagal ditambahkan.');    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::findOrFail($id); 
        return $user->name;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'status' => 'required|boolean'
        ]);

        $bidang = $user->bidang()->update([
            'user_id' => $user->id,
            'bidang_id' => $request->bidang
        ]);

        $input = $request->except(['imagebase64']);
        $name  = $request->name;        
        $roles = $request['roles'];
        $user->fill($input)->save();
            
        $uploadPhoto = !empty($request->filetype) ? 
                            $this->uploadPhoto($user,$request->imagebase64,$request->filetype) : 
                            true;

        isset($roles) ? $user->roles()->sync($roles) : $user->roles()->detach();

        $user->notify(new UserNotification('update',$user));        

        return redirect()->route('chambers.users.index')
            ->with('flash_message',
            'Data '.$name.' berhasil dirubah.');
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
}
