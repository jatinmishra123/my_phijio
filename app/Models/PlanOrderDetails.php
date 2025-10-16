<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanOrderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'expire_date',
    ];

    public function user()
    {
        return $this->belongsTo(UserTable::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
