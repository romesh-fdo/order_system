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
        $token = $request->cookie('jwt');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'redirect' => route('login'),
                'notify' => true,
            ]);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $user_role_code = $decoded->role ?? null;

            if (empty($roles)) {
                return redirect()->route('login');
            }

            $roles_array = explode('|', $roles[0]);

            if ($user_role_code === null || empty($roles_array)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'redirect' => route('login'),
                    'notify' => true,
                ]);
            }

            if (!in_array($user_role_code, $roles_array)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'redirect' => route('login'),
                    'notify' => true,
                ]);
            }

        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
