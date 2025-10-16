<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysiotherapistKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kit_name',
        'description',
        'benefits',
        'price',
        'poster_image',
        'terms_and_conditions'
    ];

    protected $casts = [
        'benefits' => 'array',
        'terms_and_conditions' => 'array',
    ];

}
