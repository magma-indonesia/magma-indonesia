<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StatistikLogin;
use App\StatistikLoginVar;
use App\v1\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    protected function username(string $username): string
    {
        $username = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
        return $username;
    }

    protected function respondWithToken(User $user, string $token, Carbon $expired_at, array $statistik): array
    {
        return [
            'success' => '1',
            'login' => [$user->makeHidden(['id','status','log'])
                ->toArray()],
            'response' => [
                'token' => $token,
                'type' => 'bearer',
                'expired_at' => $expired_at,
                'remaining' => [
                    'days' => $expired_at->diffInDays(now()),
                    'hours' => $expired_at->diffInHours(now()),
                    'minutes' => $expired_at->diffInMinutes(now()),
                ],
                'roles' => Auth::user()->getRoleNames(),
            ],
            'statistic' => $statistik,
        ];
    }

    protected function credentials(array $validated): array
    {
        return [
            $this->username($validated['vg_nip']) => $validated['vg_nip'],
            'password' => $validated['vg_password'],
            'status' => 1
        ];
    }

    protected function statistic(User $user): array
    {
        $lastLogin = StatistikLoginVar::where('nip', $user->vg_nip)
            ->latest()->first();

        $statistik = StatistikLoginVar::updateOrCreate([
            'nip' => $user->vg_nip,
            'date' => now()->format('Y-m-d'),
        ], []);

        $statistik->increment('hit');

        return [
            'last_login' => is_null($lastLogin) ? null : $lastLogin->updated_at->format('Y-m-d H:i:s'),
            'total_login' => $statistik->hit,
        ];
    }

    protected function loginAttempt(array $validated, int $ttl = 120): array
    {
        $credentials = $this->credentials($validated);

        if (Auth::once($credentials)) {
            $user = User::where('vg_nip', $validated['vg_nip'])
                    ->firstOrFail();

            $expired_at = now()->addMinutes($ttl);

            $token = Auth::guard('api')->setTTL($ttl)->claims([
                            'roles' => Auth::user()->getRoleNames(),
                            'expired_at' => $expired_at,
                        ])->attempt($credentials);

            $statistik = $this->statistic($user);

            return $this->respondWithToken($user, $token, $expired_at, $statistik);
        }

        return ['success' => '0', 'error' => 'Unauthorized'];
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vg_nip' => 'required',
            'vg_password' => 'required',
        ]);

        return response()->json(
            $this->loginAttempt($validated)
        );
    }

    public function status(): JsonResponse
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $exception) {
            return $this->ApiException(419, $exception->getMessage());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $exception) {
            return $this->ApiException(419, $exception->getMessage());
        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $exception) {
            return $this->ApiException(419, $exception->getMessage());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $exception) {
            return $this->ApiException(500, 'Token Invalid');
        }

        $payload = Auth::guard('stakeholder')->payload();

        return response()->json($payload->toArray());
    }
}
