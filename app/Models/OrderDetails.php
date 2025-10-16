<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'kit_id',
        'qty',
        'user_id',
        'amount',
        'order_id',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(UserTable::class, 'user_id');
    }

    public function kit()
    {
        return $this->belongsTo(PhysiotherapistKit::class, 'kit_id');
    }

}
