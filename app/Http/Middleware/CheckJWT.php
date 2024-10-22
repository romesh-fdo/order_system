<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;

class CheckJWT
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('jwt');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'redirect' => route('login'),
                'notify' => true,
            ]);
        }
    
        return $next($request);
    }
}
