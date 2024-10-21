<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CheckJWT
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth_header = $request->header('Authorization');
        if(!$auth_header || !str_starts_with($auth_header, 'Bearer '))
        { 
            return response()->json(['message' => 'Authorization token not found'], 401); 
        }

        $token = str_replace('Bearer ', '', $auth_header);

        try
        {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $request->attributes->add(['decoded' => $decoded]);
        } 
        catch (Exception $e)
        { 
            return response()->json(['message' => 'Invalid token'], 401); 
        }

        return $next($request);
    }
}
