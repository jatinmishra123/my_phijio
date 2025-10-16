<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\FlagScope;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointment';

    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        return static::addGlobalScope(new FlagScope);
    }
}
