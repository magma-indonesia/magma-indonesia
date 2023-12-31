<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StatistikLogin;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Carbon;
use App\Jobs\SendLoginNotification;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $request;

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
        return filter_var(request()->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
    }

    public function login(Request $request)
    {
        $request->merge([
            $this->username() => $request->username,
            'password' => $request->password,
            'status' => 1,
        ]);

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = request()->user();

            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => last($request->getClientIps())
            ]);

            StatistikLogin::updateOrCreate([
                'ip_address' => last($request->getClientIps()),
                'nip' => $user->nip,
                'date' => now()->format('Y-m-d'),
            ],[
            ])->increment('hit');

            // SendLoginNotification::dispatch('web',$user)
            //     ->delay(now()->addSeconds(3));

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
