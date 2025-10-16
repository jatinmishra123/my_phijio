<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public static function index()
    {
        $data['hospitals'] = Common::gethospital();
        return view('admin.chamber.list', $data);
    }

    public static function addnew()
    {
        $data['locations'] = Common::listnamewise('locationsssssss', 'location_name');
        return view('admin.chamber.add', $data);
    }

    public static function edit(Request $request)
    {
        $data['locations'] = Common::listnamewise('locationsssssss', 'location_name');
        $data['items'] = Common::getlistcommon('chamber', 'id', $request->id);

        return view('admin.chamber.edit', $data);
    }



    public static function addhospital(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'chamber_name' => 'required|max:255',
            'location' => 'required',
            'clinic_address' => 'required',
            'contact_number' => 'required|unique:chamber,mobile'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        } else {

            $savedata['name']        = $request->chamber_name;
            $savedata['mobile']            = $request->contact_number ?? '';
            $savedata['location_id']      = $request->location ?? '';
            $savedata['consultant_name']      = $request->consultant_name ?? '';
            $savedata['address']      = $request->clinic_address;

            $getlastid = Common::getlastid('chamber');
            if (!empty($getlastid->unique_id)) {
                $unique_id = str_replace("TMC-", "", $getlastid->unique_id) + 1;
                $savedata['unique_id'] = "TMC-" . $unique_id;
            } else {
                $savedata['unique_id'] = "TMC-10000";
            }


            // $savedata['latitude']      = $request->latitude?? '';
            // $savedata['longitude']      = $request->longitude?? '';

            // if (!empty($request->file('image'))) {
            //     $file = $request->file('image');
            //     date_default_timezone_set('Asia/Kolkata');

            //     $filename = date('YmdHi') . $file->getClientOriginalName();
            //     $file->move(public_path('uploads/chamber'), $filename);
            //     $savedata['image'] = $filename;
            // }

            $query = DB::table('chamber')->insertGetId($savedata);

            if ($query) {
                return response()->json(['code' => 200, 'message' => 'Center Added Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }
        }
    }


    public static function update(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'chamber_name' => 'required|max:255',
            'location' => 'required',
            'clinic_address' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        } else {

            $savedata['name']        = $request->chamber_name;
            $savedata['mobile']            = $request->contact_number ?? '';
            $savedata['location_id']      = $request->location ?? '';
            $savedata['consultant_name']      = $request->consultant_name ?? '';
            $savedata['address']      = $request->clinic_address;
            $savedata['latitude']      = $request->latitude?? '';
            $savedata['longitude']      = $request->longitude?? '';



            if (!empty($request->file('image'))) {
                $file = $request->file('image');
                date_default_timezone_set('Asia/Kolkata');

                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('uploads/chamber'), $filename);
                $savedata['image'] = $filename;
            }

            $query = DB::table('chamber')->where('id', $request->rowid)->update($savedata);

            if ($query) {
                return response()->json(['code' => 200, 'message' => 'Center Updated Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }
        }
    }
}
