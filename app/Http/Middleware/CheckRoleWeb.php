<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;

class CheckRoleWeb
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $token = $request->cookie('jwt');

        if(!$token)
        {
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
