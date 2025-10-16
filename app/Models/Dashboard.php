<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dashboard extends Model
{
    use HasFactory;
    protected $table = 'admin';


    public static function scopegetsidebardetails($query) {
        $check = $query->where('flag','!=','2')->get();
        return $check;
    }
    
    
    public static function getvendordetail($value,$column) {
        return DB::table('admin')->where('id', $column)->where('flag', '0')->select($value)->first();    
    }
    
    
    public  static function getwebdetail($value) {
        return DB::table('webdetails')->where('flag', '0')->select($value)->first();    
    }


}
