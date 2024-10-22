<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use App\Models\ArticleView;
use App\Models\Token;
use App\Models\UserDeviceType;
use App\Models\ActivityLog;
use App\Models\SigninLog;
use App\Models\SuppressedEmail;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;

class Helper
{
    public static function getAuth($request)
    {
        $token = $request->cookie('jwt');

        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        $request->attributes->add(['decoded' => $decoded]);

        $user = User::find($decoded->sub);
        if($user)
        {
            return $user;
        }
        else
        {
            return null;
        }
    }
}
