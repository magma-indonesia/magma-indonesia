<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

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

            if (! $token = JWTAuth::attempt($credentials)) 
            {

                if ($this->hasTooManyLoginAttempts($request)) {
                    $this->fireLockoutEvent($request);

                    return $this->sendLockoutResponse($request);
                }

                $this->incrementLoginAttempts($request);

                throw ValidationException::withMessages([
                    $request->username => [trans('auth.failed')],
                ]);

            }

            Auth::attempt([$username => $request->username, 'password' => $request->password]);

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
