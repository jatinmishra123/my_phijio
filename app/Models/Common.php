<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Common extends Model
{
    use HasFactory;
    protected $table = 'vendor';

    public static function list($table)
    {
        return DB::table($table)->where('flag', '!=', '2')->orderBy('id', 'desc')->select('*')->get();
    }

    public static function listnamewise($table, $column)
    {
        return DB::table($table)->where('flag', '!=', '2')->orderBy($column, 'asc')->select('*')->get();
    }

    public static function getlastid($table, $select = 'unique_id')
    {
        return DB::table($table)->orderBy('id', 'desc')->select($select)->first();
    }


    public static function getlistcommon($table, $where, $id)
    {
        return DB::table($table)->where($where, $id)->where('flag', '!=', '2')->select('*')->first();
    }

    public static function getall($table)
    {
        return DB::table($table)->where('flag', '0')->get();
    }


    public static function stategroupby()
    {
        return DB::table('pincodes')->where('flag', '!=', '2')->groupBy('state_name')->orderBy('state_name', 'asc')->select('state_name')->get();
    }

    public static function citygroupby()
    {
        return DB::table('pincodes')->where('flag', '!=', '2')->groupBy('city_name')->orderBy('city_name', 'asc')->select('city_name')->get();
    }

    public static function getpincode()
    {
        return DB::table('pincodes')->where('flag', '!=', '2')->groupBy('pincode')->orderBy('pincode', 'asc')->select('pincode')->get();
    }

    public static function getcategory()
    {
        return DB::table('category as c')->join('locationsssssss as l', 'c.locationId', '=', 'l.id', 'left')->where('c.flag', '!=', '2')->orderBy('c.id', 'desc')->get(['c.*', 'l.location_name']);
    }
    public static function getsubcat()
    {
        return     DB::table('subcategory as s')->join('category as c', 's.catId', '=', 'c.id', 'left')->where('c.flag', '!=', '2')->orderBy('c.id', 'desc')->get(['s.*', 'c.category_name']);
    }



    public static function gethospital()
    {
        return DB::table('chamber as c')->join('locationsssssss as l', 'c.location_id', '=', 'l.id', 'left')->where('c.flag', '!=', '2')->orderBy('c.id', 'desc')->get(['c.*', 'l.location_name']);
    }

    // GET COUNT
    public static function getCount($table)
    {
        return DB::table($table)->where('flag', '!=', '2')->select('*')->count();
    }
    public static function getCountCondition($table, $where = '', $value = '',$true="true")
    {
       $query =  DB::table($table)->where('flag', '!=', '2')->where($where, $value);
       return ($true =="true" )? $query->count() : $query->orderBy('id', 'desc')->get();

    }
    // center count
    public static function getCountCenter($table, $where = '', $value = '')
    {
        return DB::table($table)->where($where, $value)->select('*')->count();
    }
}
