<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    use HasFactory;

    protected $table = 'booking_payments';

    protected $fillable = [
        'order_id',
        'status',
        'payment_detail',
    ];
}
