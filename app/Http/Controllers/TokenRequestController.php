<?php

namespace App\Http\Controllers;

use App\TokenRequest;
use Illuminate\Http\Request;

class TokenRequestController extends Controller
{
    public function index()
    {
        $tokens = TokenRequest::where('nip', auth()->user()->nip)
                    ->orderBy('date','desc')
                    ->get();

        return view('token.index', ['tokens' => $tokens]);
    }

    public function generate()
    {
        $token = TokenRequest::where('nip', auth()->user()->nip)
            ->where('date', now()->format('Y-m-d'))
            ->first();

        if (optional($token)->count > 5) {
            return redirect()->route('chambers.token.index')
                ->with('failed', 'Request Token pada hari ini sudah dilakukan lebih dari 5x, silahkan coba lagi besok.');
        }

        $token = auth('api')->setTTL(1440)->login(auth()->user());

        TokenRequest::firstOrCreate([
            'date' => now()->format('Y-m-d'),
            'nip' => auth()->user()->nip,
        ])->increment('count');

        return redirect()->route('chambers.token.index')
            ->with('success', $token);
    }
}
