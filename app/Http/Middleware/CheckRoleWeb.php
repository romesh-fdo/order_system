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

        try
        {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $request->auth = $decoded;

            $user_role_code = $decoded->role ?? null;

            if(empty($roles))
            {
                return redirect()->route('login');
            }

            $roles_array = explode('|', $roles[0]);

            if(!Auth::check())
            {
                $user = User::find($decoded->sub);
                if($user)
                {
                    Auth::login($user);
                }
                else
                {
                    return redirect()->route('login');
                }
            }

            if ($user_role_code === null || !in_array($user_role_code, $roles_array))
            {
                return redirect()->route('login');
            }
        }
        catch (ExpiredException $e)
        {
            return redirect()->route('login');
        }
        catch (\Exception $e)
        {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
