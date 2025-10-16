<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class SubCategoryController extends Controller
{
    public static function index()
    { {
            $data['categories'] = Common::getsubcat();
            $data['ocategories'] = Common::getcategory();
            return view('admin.subcat.index', $data);
        }
    }


    public static function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'location' => 'required',
            'icon' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff |max:4096',

        ], [
            'name.required' => 'Sub Category  is  required',
            'location' => 'Category is required'
        ]);
        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        if ($request->has('icon')) {
            $file = $request->file('icon');
            date_default_timezone_set('Asia/Kolkata');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/category/'), $filename);
            $data['icon'] = $filename;
        }
        $data['unique_id'] = time();

        $data['sub_cat_name'] = $request->post('name');
        $data['catId'] = $request->post('location');

        $query = DB::table('subcategory')->insertGetId($data);

        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Sub  Category Added Succusfully']);
        } else {
            return response()->json(['code' => '401', 'message' => 'Something went wrong']);
        }
    }


    public static function edit(Request $request)
    {
        $data['ocategories'] = Common::getcategory();

        // $data['locations'] = Common::listnamewise('locationsssssss', 'location_name');
        $data['item'] = Common::getlistcommon('subcategory', 'id', $request->id);
        return view('admin/subcat/edit', $data);
    }


    public static function update(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'category_name' => 'required',
            'location' => 'required',
        ], [
            'name.required' => 'Sub Category is required'
        ]);
        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        // if (!empty($request->file('icon'))) {
        //     $file = $request->file('icon');
        //     date_default_timezone_set('Asia/Kolkata');

        //     $filename = date('YmdHi') . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/category/'), $filename);
        //     $data['icon'] = $filename;
        // }


        $data['sub_cat_name'] = $request->post('category_name');
        $data['catId'] = $request->post('location');

        $id = $request->post('rowid');

        $query = DB::table('subcategory')->where('id', $id)->update($data);
        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Sub Category Updated Succusfully']);
        }
    }
}
