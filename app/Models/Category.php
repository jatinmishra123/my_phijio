<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    public function belonglocation(){   
        return $this->belongsTo(Location::class,'location_id','id');
    }
}
