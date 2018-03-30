<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use App\User;
use App\Http\Resources\UserResource;
use App\Notifications\UserLogin;
use App\Notifications\User As UserNotification;

class UserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
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

            if (Auth::once([$username => $request->username, 'password' => $request->password, 'status' => 1]))
            {
                $user = Auth::user();
                $user->notify(new UserLogin('api',$user));
                $token = Auth::guard('api')->attempt($credentials);

                return response()->json([
                    'success' => true,
                    'data' => new UserResource($user),
                    'token' => $token
                ]);
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

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    public function login(Request $request)
    {

        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        return $this->loginAttempt($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nip)
    {   
        $user = User::where('nip',$nip)->first();
        return new UserResource($user);
    }

    public function logout(Request $request)
    {
        $user = JWTAuth::setToken($request->token)->invalidate();
        
        return response()->json([
            'data' => [ 'success' => true, 'message' => 'Berhasil menghapus token'],
        ]);
    }
    

}
