<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role', 'code'];

    const SUPER_ADMIN = 'SUPER_ADMIN';
    const CUSTOMER = 'CUSTOMER';

    protected static $role_list = [
        'SUPER_ADMIN' => 'Super Admin',
        'CUSTOMER' => 'Customer',
    ];

    public static function getIdByCode($code)
    {
        return self::where('code', $code)->value('id');
    }

    public static function getAll()
    {
        return self::$role_list;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
