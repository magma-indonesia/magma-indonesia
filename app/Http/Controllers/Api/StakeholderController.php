<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Stakeholder;
use App\Notifications\UserLogin;
use Carbon\Carbon;
use JWTAuth;

class StakeholderController extends Controller
{
    
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

        $stakeholder = Stakeholder::where('uuid', $request->app_id)
                ->where('secret_key', $request->secret_key)
                ->firstOrFail();

        if ($stakeholder->status == 0) {
            return response()->json(
                ['success' => false, 'error' => 'APP_ID tidak aktif'], 403);
        }

        if ($stakeholder->expired_at->lt(now())) {
            return response()->json(
                ['success' => false, 'error' => 'APP_ID telah habis masa berlaku'], 403);
        }

        return $this->loginAttempt($stakeholder);
    }

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
            'days_remaining' => $this->getExpired()->remaining,
            'expired_at' => $this->getExpired()->expired_at,
        ]);
    }

    protected function setExpired($stakeholder)
    {
        $expired_at = $stakeholder->expired_at;
        $remaining = $expired_at->diffInDays(now());

        $this->expired = (object) [
            'remaining' => $remaining,
            'expired_at' => $expired_at->format('Y-m-d H:i:s')
        ];

        return $this;
    }

    protected function getExpired()
    {
        return $this->expired;
    }

    protected function loginAttempt($stakeholder)
    {
        try {

            $days = $this->setExpired($stakeholder)
                        ->getExpired()->remaining+1;

            $claims = [
                'days_remaining' => $this->getExpired()->remaining,
                'expired_at' => $this->getExpired()->expired_at
            ];

            $token = auth('stakeholder')->setTTL($days*1440)
                        ->claims($claims)
                        ->login($stakeholder);

            if ($token)
                return $this->respondWithToken($token);

            return response()->json(['error' => 'Unauthorized'], 401);

        }

        catch (JWTException $e) {
            return response()->json(['success' => false, 'error' => 'Token tidak bisa dibuat'], 500);
        }

    }

    public function status(Request $request)
    {
       
        try {
            JWTAuth::parseToken()->authenticate();
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

        $payload = auth('stakeholder')->payload();

        return $payload->toArray();
    }

    /**
     * Undocumented function
     *
     * @param int $code
     * @param string $message
     * @return json
     */
    protected function ApiException(int $code, string $message)
    {
        return response()->json([
            'status' => 'false',
            'code' => $code,
            'message' => $message], $code);
    }

}
