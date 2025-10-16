<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Blogs extends Model
{
    use HasFactory;
    protected $table = 'blogs';


    public  static function list($where='',$value='') {
        if($where!=''){
          return   DB::table('blogs as b')
            ->join('vendor as v', 'v.id', '=', 'b.writer_id')
            ->where('b.flag','!=', '2')
            ->where('b.'.$where, $value)
            ->select('b.*', 'v.vendor_name')
            ->get(); 

        }else{
        return DB::table('blogs as b')
                    ->join('vendor as v', 'v.id', '=', 'b.writer_id')
                    ->where('b.flag','!=', '2')
                    ->select('b.*', 'v.vendor_name')
                    ->get();
        }      
    }
   

}
