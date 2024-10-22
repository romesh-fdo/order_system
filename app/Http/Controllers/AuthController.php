<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;
use App\Models\Role;

//use App\Helpers\Helper;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'validate_errors' => $validator->errors(),
                'notify' => true,
            ]);
        }
    
        $credentials = $request->only('username', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password',
                'notify' => true,
            ]);
        }
    
        $user = Auth::user();
        
        $token = JWT::encode(
            [
                'sub' => $user->id,
                'role' => $user->role->code,
                'iat' => time(),
                'exp' => time() + 60 * 60,
            ],
            env('JWT_SECRET'),
            'HS256'
        );

        if($user->role->code == Role::SUPER_ADMIN)
        {
            $redirect_url = route('dashboard');
        }
        else
        {
            $redirect_url = route('order');
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'notify' => true,
            'redirect' => $redirect_url,
        ])->cookie('jwt', $token, 60, null, null, false, true);
    }

    public function logout(Request $request)
    {
        $token = cookie('jwt', null, 0);

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
            'notify' => true,
            'redirect' => route('login')
        ])->cookie($token);
    }
}
