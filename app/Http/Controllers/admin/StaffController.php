<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Common;
use App\Models\UserTable;
use App\Models\AllotedChamber;


class StaffController extends Controller
{
    public static function index()
    {
        $data['staff'] = UserTable::where('genre', 'staff')->orderBy('id', 'desc')->get();
        return view('admin.staff.list', $data);
    }

    public static function addnew()
    {
        $data['chamber'] = Common::listnamewise('chamber', 'name');
        return view('admin.staff.add', $data);
    }


    public static function edit(Request $request)
    {
        $data['items'] = Common::getlistcommon('user_table', 'id', $request->id);
        $data['chamber'] = Common::listnamewise('chamber', 'name');
        $data['assignchamner'] = Common::getlistcommon('doctor_chamber', 'doctorid', $request->id);

        return view('admin.staff.edit', $data);
    }


    public static function profile(Request $request)
    {
        $data['user_table'] = Common::getlistcommon('user_table', 'id', $request->id);
        return view('admin.staff.profile', $data);
    }



    public static function addstaff(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'phone' => 'required|min:9|max:12',
            'password' => 'required|min:6',
            'email' => 'required|unique:user_table',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        } else {
            $userQuery['name'] = $request->name;
            $userQuery['phone'] = $request->phone;
            $userQuery['email'] = $request->email;
            $userQuery['password'] = Hash::make($request->password);

            // if (!empty($request->file('image'))) {
            //     $file = $request->file('image');
            //     date_default_timezone_set('Asia/Kolkata');

            //     $filename = date('YmdHi') . $file->getClientOriginalName();
            //     $file->move(public_path('uploads/staff/image'), $filename);
            //     $userQuery['image'] = $filename;
            // }

            $userQuery['genre'] = 'staff';
            $query = DB::table('user_table')->insertGetId($userQuery);


            if ($query) {
                // here insert  in doctor chamber table when staff is added
                // DB::table('doctor_chamber')->insert(['doctorid'=>$query,'chamberId'=>$request->chamber]);
                return response()->json(['code' => 200, 'message' => 'Nurse Added Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }
        }
    }




    public static function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'phone' => 'required|min:9|max:12',
            'email' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        } else {
            $userQuery['name'] = $request->name;
            $userQuery['phone'] = $request->phone;
            $userQuery['email'] = $request->email;
            $userQuery['password'] = Hash::make($request->password);

            if (!empty($request->file('image'))) {
                $file = $request->file('image');
                date_default_timezone_set('Asia/Kolkata');

                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('uploads/staff/image'), $filename);
                $userQuery['image'] = $filename;
            }

            $userQuery['genre'] = 'staff';



            $allotedchamber = AllotedChamber::updateOrCreate(['doctorId' => $request->rowid], [
                'chamberId' => $request->chamber
            ]);




            $result = DB::table('user_table')->where('id', $request->rowid)->update($userQuery);



            if ($result) {
                return response()->json(['code' => 200, 'message' => 'Employee Updated Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }
        }
    }



    public static function changepassworddiv($id, $table)
    {
        $data['id'] = $id;
        $data['table'] = $table;
        return view('admin/staff/change_password', $data);
    }



    public static function updatepassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'rowid' => 'required',
            'table' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        } else {

            if ($request->new_password == $request->confirm_password) {

                $savedata['password'] = Hash::make($request->new_password);
                $query = DB::table($request->table)->where('id', $request->rowid)->update($savedata);

                if ($query) {
                    return response()->json(['code' => 200, 'message' => 'Password Changed Successfully']);
                } else {
                    return response()->json(['code' => 400, 'message' => 'Something went wrong']);
                }
            } else {
                return response()->json(['code' => 400, 'message' => 'Password & Confirm Password must be same']);
            }
        }
    }




    public static function delete(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'id' => 'required',
            'table' => 'required',
            'type' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 400, 'message' => 'Something went wrong']);
        } else {

            if ($request->type == 'activate') {
                $savedata['flag'] = 0;
                $key = 'Activated';
            } else if ($request->type == 'deactivate') {
                $savedata['flag'] = 1;
                $key = 'Deactivated';
            } else if ($request->type == 'delete') {
                $savedata['flag'] = 2;
                $key = 'Deleted';
                $query = DB::table($request->table)->where('id', $request->id)->delete();
                return response()->json(['code' => 200, 'message' => 'Data ' . $key . ' Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }

            $query = DB::table($request->table)->where('id', $request->id)->update($savedata);

            if ($query) {
                return response()->json(['code' => 200, 'message' => 'Data ' . $key . ' Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }
        }
    }
}
