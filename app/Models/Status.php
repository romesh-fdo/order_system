<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'code', 'badge'];

    const NEW_ORDER = 'NEW_ORDER';
    const IN_PROGRESS = 'IN_PROGRESS';
    const COMPLETED = 'COMPLETED';
    const CANCELLED = 'CANCELLED';

    protected static $status_list = [
        'NEW_ORDER' => 'New Order',
        'IN_PROGRESS' => 'In Progress',
        'COMPLETED' => 'Completed',
        'CANCELLED' => 'Cancelled',
    ];

    public static function getIdByCode($code)
    {
        return self::where('code', $code)->value('id');
    }

    public static function getAll()
    {
        return self::$status_list;
    }
}
