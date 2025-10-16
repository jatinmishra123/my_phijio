<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auth extends Model
{
    use HasFactory;
    protected $table = 'user_table';

    public static function checkemail($email)
    {
        $check = Auth::where('genre', 'admin')->where('email', $email)->first();
        return $check;
    }

    public  static function listwhere($id)
    {
        return Auth::where('id', $id)->first();
    }
}
