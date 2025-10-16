<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'address_id',
        'slots',
        'session_type',
        'booking_id',
        'note',
        'name',
        'gender',
        'age',
        'level',
        'level_amount',
        'category_id',
    ];


    public function doctorInfo()
    {
        return $this->belongsTo(UserTable::class, 'doctor_id');
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }


    public function userInfo()
    {
        return $this->belongsTo(UserTable::class, 'user_id');
    }




    public function payment()
    {
        return $this->hasOne(BookingPayment::class, 'order_id');
    }
}
