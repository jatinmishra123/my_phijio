<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otpVerify extends Model
{
    protected $table  = 'otpverify';
    protected  $fillable = ['id','phone','email','otp','is_verify'];

    use HasFactory;
}
