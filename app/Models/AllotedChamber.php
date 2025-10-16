<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotedChamber extends Model
{
    protected $fillable  = ['doctorId','chamberId'];
    protected   $table   = 'doctor_chamber';
    use HasFactory;

     public function Doctors(){
         return $this->hasMany(UserTable::class,'id','doctorId');
     }

}
