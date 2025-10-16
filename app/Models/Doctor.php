<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $table = 'doctor';
    protected   $fillable =

    [
        'doctor_id',
        'unique_id',
        'dob',
        'gender',
        'address_line_1',
        'address_line_2',
        'country',
        'state',
        'city',
        'zipcode',
        'degree',
        'college',
        'completion_year',
        'description',
        'category_id',
        'experience_year',
        'previous_orgnisation',
        'area_of_expertise',
        'current_workplace',
        'bank_name',
        'holder_name',
        'cheque',
        'account_number',
        'ifsc_code',
        'upi_id',
        'employment_type',
        'willing_to_travel',
        'emergency',
        'relation',
        'referral_code',
        'adhar_proof',
        'pan_proof',
        'degree_proof',
        'registration_proof',
        'video_proof',
        'signature',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
