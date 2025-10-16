<?php

namespace App\Http\Controllers;

use App\Models\Common;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class LocationController extends Controller
{
    public static function index()
    {
        $data['locations'] = Common::list('locationsssssss');
        return view('admin.location.index', $data);
    }
    public static function degreeindex()
    {
        $data['locations'] = Common::list('degrees');
        return view('admin.degrees.index', $data);
    }
    public static function ceritificatesindex()
    {
        $data['locations'] = Common::list('certificates');
        return view('admin.certificates.index', $data);
    }


    public static function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'location_name' => ['required', Rule::unique('locationsssssss')->where(function ($query) use ($request) {
                return $query->where('flag', '!=', '2');
            })],
        ], [
            'location_name.required' => 'Location  is required'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        $data['location_name'] = $request->post('location_name');

        $query = DB::table('locationsssssss')->insertGetId($data);
        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Location Added Successfully']);
        }
    }

    public static function degreestore(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'location_name' => ['required', Rule::unique('degrees')->where(function ($query) use ($request) {
                return $query->where('flag', '!=', '2');
            })],
        ], [
            'location_name.required' => 'Degree  is required'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        $data['location_name'] = $request->post('location_name');

        $query = DB::table('degrees')->insertGetId($data);
        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Degree Added Successfully']);
        }
    }
    public static function ceritificatestore(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'location_name' => ['required', Rule::unique('certificates')->where(function ($query) use ($request) {
                return $query->where('flag', '!=', '2');
            })],
        ], [
            'location_name.required' => 'Certificate  is required'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        $data['location_name'] = $request->post('location_name');

        $query = DB::table('certificates')->insertGetId($data);
        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Certificate Added Successfully']);
        }
    }


    public static function edit(Request $request)
    {
        $data['item'] = Common::getlistcommon('locationsssssss', 'id', $request->id);
        return view('admin/location/edit', $data);
    }
    public static function degreeedit(Request $request)
    {
        $data['item'] = Common::getlistcommon('degrees', 'id', $request->id);
        return view('admin/degrees/edit', $data);
    }
    public static function ceritificateedit(Request $request)
    {
        $data['item'] = Common::getlistcommon('certificates', 'id', $request->id);
        return view('admin/certificates/edit', $data);
    }


    public static function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'location_name' => ['required', Rule::unique('locationsssssss')->where(function ($query) use ($request) {
                return $query->where('flag', '!=', '2');
            })],
        ], [
            'location_name.required' => 'Location  is required'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        $data['location_name'] = $request->post('location_name');
        $id   = $request->rowid;

        $query = DB::table('locationsssssss')->where('id', $id)->update($data);

        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Location Added Successfully']);
        }
    }
      public static function degreeupdate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'location_name' => ['required', Rule::unique('degrees')->where(function ($query) use ($request) {
                return $query->where('flag', '!=', '2');
            })],
        ], [
            'location_name.required' => 'Degree  is required'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        $data['location_name'] = $request->post('location_name');
        $id   = $request->rowid;

        $query = DB::table('degrees')->where('id', $id)->update($data);

        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Degree Updated Successfully']);
        }
    }
      public static function certificatesupdate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'location_name' => ['required', Rule::unique('certificates')->where(function ($query) use ($request) {
                return $query->where('flag', '!=', '2');
            })],
        ], [
            'location_name.required' => 'Certificate  is required'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        }

        $data['location_name'] = $request->post('location_name');
        $id   = $request->rowid;

        $query = DB::table('certificates')->where('id', $id)->update($data);

        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Certificate Updated Successfully']);
        }
    }
}
