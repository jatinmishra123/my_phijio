<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class PlanController extends Controller
{
    public static function index()
    { 
            $data['plans'] = DB::table('plans')->orderBy('id','desc')->get();
            // return $data;
            $data['categories'] = Common::getcategory();
            return view('admin.plans.index', $data);
        
    }
    public static function bannerindex()
    { 
             $data['categories'] =DB::table('banner')->orderby('id','desc')->get();
            return view('admin.banners.index', $data);
        
    }
    
   

   public function store(Request $request)
{
       $validate = Validator::make($request->all(), [
        'title'          => 'required|string|max:255',
        'type'           => 'required|in:standard,premium',
        'duration'       => 'required|integer|min:1',
        'duration_type'  => 'required|in:month,year',
        'price'          => 'required|numeric|min:0',
        'benefits'       => 'required|string',
    ]);
    
      if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

    $benefits = array_map('trim', explode(',', $request->benefits));

  DB::table('plans')->insert([
    'title'          => $request->title,
    'type'           => $request->type,
    'duration'       => $request->duration,
    'duration_type'  => $request->duration_type,
    'description'  => $request->description,
    'price'          => $request->price,
    'benefits'       => json_encode($benefits),
    'is_active'      => 1,
    'created_at'     => now(),
    'updated_at'     => now()
]);


            return response()->json(['code' => '200', 'message' => 'Plan Added Succusfully']);
}

    
      public static function bannerstore(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_for' => 'required',
            'icon' => 'required|mimes:jpg,jpeg,png,bmp,tiff |max:4096',

        ]);
        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        if (!empty($request->file('icon'))) {
            $file = $request->file('icon');
            date_default_timezone_set('Asia/Kolkata');

            $filename = date('YmdHi') . str_replace(' ', '', $file->getClientOriginalName());             
                    $file->move(public_path('uploads/category/'), $filename);
                    $data['banner'] = $filename;
                }

        // $data['category_name'] = $request->post('name');
        $data['category_for'] = $request->post('category_for');

        $query = DB::table('banner')->insertGetId($data);

        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Banner Added Succusfully']);
        } else {
            return response()->json(['code' => '401', 'message' => 'Something went wrong']);
        }
    }


    public static function edit(Request $request)
    {
        
        
            $data['item'] = Common::getlistcommon('category', 'id', $request->id);

    // Fetch the related category_levels grouped by level
      $data['levels'] = DB::table('category_levels')
        ->where('category_id', $request->id)
        ->get()
        ->groupBy('level');

    return view('admin/category/edit', $data);

    }
    
     public static function banneredit(Request $request)
    {
        $data['locations'] = Common::listnamewise('locationsssssss', 'location_name');
        $data['item'] = Common::getlistcommon('banner', 'id', $request->id);
        return view('admin/banners/edit', $data);
    }


  public static function update(Request $request)
{
    $validate = Validator::make($request->all(), [
        'category_name' => 'required',
        'category_for' => 'required',
        'icon' => 'mimes:jpg,jpeg,png,bmp,tiff|max:4096',
    ], [
        'category_name.required' => 'Category is required'
    ]);

    if ($validate->fails()) {
        return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
    }

    $data = [];
    $data['category_name'] = $request->post('category_name');
    $data['category_for'] = $request->post('category_for');
    $data['locationId'] = $request->post('location');
      $data['description'] = $request->post('description') ?? '';

    if (!empty($request->file('icon'))) {
        $file = $request->file('icon');
        date_default_timezone_set('Asia/Kolkata');
        $filename = date('YmdHi') . str_replace(' ', '', $file->getClientOriginalName());
        $file->move(public_path('uploads/category/'), $filename);
        $data['icon'] = $filename;
    }

    $id = $request->post('rowid');

    // Update category table
    DB::table('category')->where('id', $id)->update($data);

    // Remove existing level entries
    DB::table('category_levels')->where('category_id', $id)->delete();

    // Re-insert new level data
    if ($request->has('levels')) {
        foreach ($request->levels as $level => $sessions) {
            foreach (['weekly', 'monthly', 'yearly'] as $type) {
                $sessionCount = $sessions[$type]['sessions'] ?? null;
                $price = $sessions[$type]['price'] ?? null;

                if (!is_null($sessionCount) || !is_null($price)) {
                    DB::table('category_levels')->insert([
                        'category_id' => $id,
                        'level' => $level,
                        'session_type' => $type,
                        'sessions' => $sessionCount,
                        'price' => $price,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }

    return response()->json(['code' => 200, 'message' => 'Category Updated Successfully']);
}

     public static function bannerupdate(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'category_for' => 'required',

            'icon' => 'mimes:jpg,jpeg,png,bmp,tiff |max:4096',
        ]);
        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }
             $data['category_for'] = $request->post('category_for');


        if (!empty($request->file('icon'))) {
            $file = $request->file('icon');
            date_default_timezone_set('Asia/Kolkata');

        $filename = date('YmdHi') . str_replace(' ', '', $file->getClientOriginalName());             
            $file->move(public_path('uploads/category/'), $filename);
            $data['banner'] = $filename;
        }

        $data['category_for'] = $request->post('category_for');


        $id = $request->post('rowid');

        $query = DB::table('banner')->where('id', $id)->update($data);
        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Banner Updated Succusfully']);
        }
    }
}
