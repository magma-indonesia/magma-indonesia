<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use App\User;
use App\UserPhoto;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Notifications\UserLogin;
use App\Notifications\User As UserNotification;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class UserController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Uploading photo profile user
     *
     * @return boolean
     */
    protected function uploadPhoto($nip,$photo,$filetype)
    {

        list($type, $photo) = explode(';', $photo);
        list(, $photo)      = explode(',', $photo);
        $photo = base64_decode($photo);
        $photoName = uniqid().$filetype;
        
        $uploadPhoto = Storage::disk('user')->put($photoName, $photo);

        if (Storage::disk('user')->exists(optional(auth()->user()->photo)->filename))
        {
            Storage::disk('user')->delete(auth()->user()->photo->filename);
            Storage::disk('user-thumb')->delete(auth()->user()->photo->filename);
        }

        $url = Storage::disk('user')->get($photoName);

        $thumbnail = Image::make($url);
        $thumbnail->resize(76, 76)->save(storage_path('app/users/photo/thumb/'.$photoName));

        if ($uploadPhoto)
        {
            $getUserId = User::where('nip',$nip)->first();
            $success = $getUserId->photo()->updateOrCreate([
                'user_id' => $getUserId->id],[
                'filename' => $photoName
            ]);
        } else {
            return redirect()->back()->with('flash_message','Gagal upload photo User.');    
        }

        if ($success)
        {
            return true;
        }
        
        return redirect()->back()->with('flash_message','Gagal upload photo User.');    
    
    }

    /** 
     * Login Controller User
     * @param  \Illuminate\Http\Request  $request
     * @return View
    */
    protected function loginAttempt($request)
    {
        $username   = $request->username;
        $username   = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
        $request->merge([$username => $request->username]);

        $credentials = $request->only($username, 'password');

        try {

            if (Auth::attempt([$username => $request->username, 'password' => $request->password, 'status' => 1]))
            {
                $user = Auth::user();

                try {
                    $user->notify(new UserLogin('web',$user));
                }
                catch (Exception $e){
                    
                }
                
                $token = Auth::guard('api')->attempt($credentials);

                return redirect()->route('chamber')->header('Authorization','Bearer '.$token);
            }

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            $this->incrementLoginAttempts($request);

            throw ValidationException::withMessages([
                $request->username => [trans('auth.failed')],
            ]);

        } 
        
        catch (JWTException $e) {
            
            return response()->json(['success' => false,'error' => 'could_not_create_token'], 500);
            
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
        Auth::logout();
        $cookie = \Cookie::forget('token');

        if(Auth::check())
        {

            return 'Masih Login';

        }

        return redirect()->route('home');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users      = User::all();
        return view('users.index',compact('users'));

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'nip' => 'required|digits:18|unique:users,nip,NULL,id,deleted_at,NULL',
            'phone' => 'nullable|digits_between:10,12|unique:users,phone,NULL,id,deleted_at,NULL',
            'email' => 'required|string|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|boolean'
        ]);

        $input  = $request->except(['imagebase64','file']);
     
        $user = new User();
        $userSaved = $user->fill($input)->save();
        $request->has('file') ? $uploadPhoto = $this->uploadPhoto($request->nip,$request->imagebase64,$request->filetype) : $uploadPhoto = true;

        if ($userSaved AND $uploadPhoto)
        {
            $user->notify(new UserNotification('create',$user));
            return redirect()->route('users.index')->with('flash_message',$request->name.' berhasil ditambahkan.');
        } 

        return redirect()->route('users.index')->with('flash_message','User gagal ditambahkan.');    
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

        return view('users.edit', compact('user', 'roles'));
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
        //
        $user = User::findOrFail($id);
        
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'nip' => 'required|digits:18|unique:users,nip,'.$user->id,
            'phone' => 'nullable|digits_between:11,12',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'status' => 'required|boolean'
        ]);

        $input = $request->except(['imagebase64','file']);
        $name  = $request->name;        
        $roles = $request['roles'];
        $user->fill($input)->save();
            
        $request->file ? $uploadPhoto = $this->uploadPhoto($request->nip,$request->imagebase64,$request->filetype) : $uploadPhoto = true;

        if (isset($roles)) {        
            $user->roles()->sync($roles); 
        }        
        else {
            $user->roles()->detach();
        }

        $user->notify(new UserNotification('update',$user));        

        return redirect()->route('users.index')
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
        $user   = User::findOrFail($id);
        $nama   = $user->name;
        if ($user->delete()){
            $user->notify(new UserNotification('delete',$user));            
        }

        $data = [
            'success' => 1,
            'message' => $nama.' berhasil dihapus.'
        ];

        return response()->json($data);
    }
}
