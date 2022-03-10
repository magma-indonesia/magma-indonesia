<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected function username(string $username): string
    {
        $username = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';
        return $username;
    }

    protected function respondWithToken(User $user, string $token, Carbon $expired_at): array
    {
        return [
            'success' => 1,
            'login' => $user->makeHidden(['id','status','log'])
                ->toArray(),
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
            ]
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

    public function loginAttempt(array $validated, int $ttl = 120): array
    {
        $credentials = $this->credentials($validated);

        if (Auth::once($credentials)) {
            $user = User::where('vg_nip', $validated['vg_nip'])
                    ->firstOrFail();

            $token = Auth::guard('api')->setTTL($ttl)->claims([
                            'roles' => Auth::user()->getRoleNames(),
                        ])->attempt($credentials);

            $expired_at = now()->addMinutes($ttl);

            return $this->respondWithToken($user, $token, $expired_at);
        }

        return ['success' => false, 'error' => 'Unauthorized'];
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
}
