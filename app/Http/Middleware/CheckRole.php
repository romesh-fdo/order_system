<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
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
            $user_role_code = $decoded->role ?? null; 
            
            if(empty($roles))
            {
                return redirect()->route('login');
            }

            $roles_array = explode('|', $roles[0]);

            if($user_role_code === null || empty($roles_array))
            { 
                return response()->json(['message' => 'Access denied'], 403); 
            }

            if(!in_array($user_role_code, $roles_array))
            { 
                return response()->json(['message' => 'Access denied'], 403); 
            }

        }
        catch (Exception $e)
        { 
            return response()->json(['message' => 'Invalid token'], 401); 
        }

        return $next($request);
    }
}
