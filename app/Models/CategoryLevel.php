<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLevel extends Model
{
    protected $table   = 'category_levels';


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    use HasFactory;
}
