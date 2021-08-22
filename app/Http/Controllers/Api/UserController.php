<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Notifications\UserLogin;
use App\Notifications\User As UserNotification;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

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
            'expires_at' => Auth::guard('api')->factory()->getTTL() / 1440,
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
            $this->request = $request;

            $credentials = [
                $this->username() => $request->username,
                'password' => $request->password,
                'status' => 1
            ];

            if ($user = Auth::once($credentials)) {

                $claims = [
                    'roles' => auth()->user()->getRoleNames(),
                ];

                $token = auth('api')->setTTL($ttl)
                            ->claims($claims)
                            ->attempt($credentials);

                auth()->user()
                    ->notify(new UserLogin('api',auth()->user()));

                return $this->respondWithToken($token);
            }

            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Token tidak bisa dibuat'], 500);
        }
    }

    public function username()
    {
        $username = filter_var($this->request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
        return $username;
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
            'ttl' => 'sometimes|required|integer'
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

        catch (\Tymon\JWTAuth\Exceptions\JWTException $exception) {
            return $this->ApiException(500, 'Token Invalid');
        }
    }

    /**
     * Get token status
     *
     * @param Request $request
     * @return array
     */
    public function status(Request $request)
    {

        try {
            if (JWTAuth::parseToken()->authenticate()) {
                $payload = auth('api')->payload();
                return $payload->toArray();
            }

            return $this->ApiException(419, 'Token Invalid');
        }

        catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $exception) {
            return $this->ApiException(419, $exception->getMessage());
        }

        catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $exception) {
            return $this->ApiException(419, $exception->getMessage());
        }

        catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $exception) {
            return $this->ApiException(419, $exception->getMessage());
        }

        catch (\Tymon\JWTAuth\Exceptions\JWTException $exception) {
            return $this->ApiException(500, 'Token Invalid');
        }

        return $this->ApiException(419, 'Token Invalid');
    }

    /**
     * Add token to blacklist
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $user = JWTAuth::parseToken()->invalidate();

        return response()->json([
            'data' => [ 'success' => true, 'message' => 'Berhasil menghapus token'],
        ]);
    }

    /**
     * Undocumented function
     *
     * @param int $code
     * @param string $message
     * @return json
     */
    protected function ApiException(int $code = 419, string $message)
    {
        return response()->json([
            'status' => 'false',
            'code' => $code,
            'message' => $message], $code);
    }

}
