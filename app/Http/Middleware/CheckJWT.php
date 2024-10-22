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
    
        try
        {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $request->attributes->add(['decoded' => $decoded]);

            if(!Auth::check())
            {
                $user = User::find($decoded->sub);
                if($user)
                {
                    $request->attributes->add(['auth' => $user]);
                }
                else
                {
                    return redirect()->route('login');
                }
            }
        }
        catch (Exception $e)
        {
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
