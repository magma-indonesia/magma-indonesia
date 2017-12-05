<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class UserController extends Controller
{
    use AuthenticatesUsers;
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

                $token = JWTAuth::attempt($credentials);
                
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

        $res    = [
            
            'success' => true,
            'token' => $token

        ];

        return redirect()->route('chamber')->header('Authorization','Bearer '.$token);

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

        // return response()->json(['success' => true, 'message' => 'Logout Berhasil'])->withCookie($cookie);

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
        $path = $request->file('file')->store('photo','press');
        return $path;
        
        return $file->getSize();
        // $this->validate($request, [
        //     'name' => 'required|string|max:255',
        //     'nip' => 'required|digits:18|unique:users',
        //     'phone' => 'nullable|digits_between:10,12|unique:users',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:6|confirmed',
        //     'status' => 'required|boolean'
        // ]);

        // $input  = $request->all();
        // $user   = new User();

        // if ($user->fill($input)->save())
        // {
        //     return redirect()->route('users.index')->with('flash_message',$request->name.' berhasil ditambahkan.');
        // } 

        // return redirect()->route('users.index')->with('flash_message','User gagal ditambahkan.');      

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

        $input = $request->all();
        $name  = $request->name;        
        $roles = $request['roles'];
        $user->fill($input)->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles); 
        }        
        else {
            $user->roles()->detach();
        }
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
        $user->delete();

        $data = [
            'success' => 1,
            'message' => $nama.' berhasil dihapus.'
        ];

        return response()->json($data);
    }
}
