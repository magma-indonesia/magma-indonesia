<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Carbon;
use App\Jobs\SendLoginNotification;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $maxAttempts = 2;

    protected $redirectTo = '/chambers';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.login');
    }

    public function username()
    {
        return filter_var($this->request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
    }

    public function login(Request $request)
    {
        $this->request = $request;

        $request->merge([
            $this->username() => $request->username,
            'password' => $request->password,
            'status' => 1,
        ]);
        $request->request->remove('username');

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = auth()->user();

            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => last($request->getClientIps())  
            ]);

            SendLoginNotification::dispatch('web',$user)
                ->delay(now()->addSeconds(3));

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
