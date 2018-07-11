<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Notifications\UserLogin;
use App\Notifications\User As UserNotification;

class UserController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'type' => 'bearer',
            'expires_days' => Auth::guard('api')->factory()->getTTL() / 1440
        ]);
    }

    /**
     * Get index users
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $users = User::query();
            
        $users = $users->select('id','name','nip','email','phone','last_login_at','last_login_ip');

        if ($request->has('bidang')) {
            $bidang = $request->bidang;
            $users = $users->whereHas('bidang.bidang', function($query) use ($bidang){
                $query->where('code','like',$bidang);
            });
        }

        $users = $users->paginate(30);  
        return new UserCollection($users);
        
    }

    /** 
     * Login Controller User
     * @param  \Illuminate\Http\Request  $request
     * @return View
    */
    protected function loginAttempt($request,$ttl)
    {
        try {
            $username = $request->username;
            $username = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
            $request->request->add(['status' => 1]);
            $request->merge([$username => $request->username]);
    
            $credentials = $request->only($username, 'password', 'status');

            if ($user = Auth::once($credentials)) {
                $token = Auth::guard('api')->setTTL($ttl)->attempt($credentials);
                $user = auth()->user();
                $user->notify(new UserLogin('api',$user));
                return $this->respondWithToken($token);
            }

            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            $this->incrementLoginAttempts($request);
    
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Token tidak bisa dibuat'], 500);
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

        $ttl = $request->has('ttl') ? $request->ttl*1440 : 1440 ;

        return $this->loginAttempt($request,$ttl);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nip)
    {   
        try {
            $user = User::where('nip',$nip)->first();
            return new UserResource($user); 
        }

        catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    }

    /**
     * Add token to blaclist
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $user = JWTAuth::setToken($request->token)->invalidate();
        
        return response()->json([
            'data' => [ 'success' => true, 'message' => 'Berhasil menghapus token'],
        ]);
    }

}
