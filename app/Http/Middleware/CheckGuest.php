<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;
use App\Models\Role;

class CheckGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('jwt');

        if($token)
        {
            try
            {
                $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

                $user = User::find($decoded->sub);

                if(!$user)
                {
                    return redirect()->route('login');
                }

                if($user->role->code == Role::SUPER_ADMIN)
                {
                    return redirect()->route('dashboard');
                }
                else
                {
                    return redirect()->route('order');
                }
            } 
            catch (\Exception $e)
            {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        return $next($request);
    }
}
