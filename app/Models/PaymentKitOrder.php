<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentKitOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'status',
        'payment_id',
        'transaction_data',
    ];

    protected $casts = [
        'transaction_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(OrderDetails::class);
    }
}
