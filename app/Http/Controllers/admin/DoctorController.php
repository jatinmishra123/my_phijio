<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\Doctor;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Str;
use App\Models\UserTable;

class DoctorController extends Controller
{
    public static function index(Request $request)
    {

        $role = $request->role;

        $data['role'] = Str::ucfirst($role) . 's';
        $data['doctors'] = DB::table('user_table')
            ->leftjoin('doctor', 'user_table.id', '=', 'doctor.doctor_id')
            ->leftjoin('category', 'category.id', '=', 'doctor.specialization')
            ->where('user_table.genre', '=', $role)
            ->select('user_table.*', 'user_table.id as ID', 'doctor.*', 'category.category_name', 'user_table.flag as F')
            ->orderby('user_table.id', 'desc')
            ->get();

        return view('admin.doctor.list', $data);
    }


    public static function addnew()
    {
        $data['category'] = Common::list('category');
        return view('admin.doctor.add', $data);
    }


    public static function edit(Request $request)
    {
        $data['items'] = UserTable::with('doctor')->find($request->id);
        $data['doctor'] = $data['items']->doctor; // Assign the nested doctor for easier access
        $data['category'] = DB::table('category')->get(); // Assuming specialization is from categories
        return view('admin.doctor.edit', $data);
    }



    public static function profile(Request $request)
    {
        $data['user_table'] = UserTable::with('doctor')->find($request->id);
        return view('admin.doctor.profile', $data);
    }


    public static function allot(Request $request)
    {

        // $data['items'] = Common::getlistcommon('user_table', 'id', $request->id);
        // $data['details'] = Common::getlistcommon('doctor_chamber', 'doctorId', $request->id);
        // $data['chamber'] = Common::listnamewise('chamber', 'name');
        // $data['months'] = Config::get('defined_func.ALL_MONTHS');
        // $data['weeks'] = Config::get('defined_func.ALL_WEEKS');
        // $data['days'] = Config::get('defined_func.ALL_DAYS');

        // get time slots here
        $data['timeslots'] = DB::table('timeslots')->leftjoin('user_address', 'user_address.id', 'timeslots.address_id')->where('timeslots.user_id', $request->id)->orderby('timeslots.id', 'desc')->get();
        return view('admin.doctor.allotchamber', $data);
    }


    public static function updatechamber(Request $request)
    {
        $validate = Validator::make($request->all(), [
            // 'doctor_name' => 'required',
            // 'chamber' => 'required',
            'fee' => 'required',
            'total_patient_capacity' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()]);
        } else {

            $userQuery['doctorId'] = $request->doctor_id;
            // $userQuery['chamberId'] = $request->chamber;
            $userQuery['patient'] = $request->total_patient_capacity;
            $userQuery['fee'] = $request->fee;
            $userQuery['morning_schedule'] = $request->morning_time_schedule;
            $userQuery['afternoon_schedule'] = $request->afternoon_time_schedule;
            $userQuery['evening_schedule'] = $request->evening_time_schedule;

            $userQuery['working_months'] = !empty($request->working_month) ? implode(",", $request->working_month) : '';
            $userQuery['working_weeks'] = !empty($request->working_week) ? implode(",", $request->working_week) : '';
            $userQuery['working_days'] = !empty($request->working_days) ? implode(",", $request->working_days) : '';

            $userQuery['doctor_status'] = 'Offline';

            $exist = Common::getlistcommon('doctor_chamber', 'doctorId', $request->doctor_id);

            if (empty($exist)) {
                $query = DB::table('doctor_chamber')->insertGetId($userQuery);
            } else {
                $query = DB::table('doctor_chamber')->where('doctorId', $request->doctor_id)->update($userQuery);
            }

            if ($query) {
                return response()->json(['code' => 200, 'message' => 'Availibility set Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }
        }
    }
    // this is add doctor api make the new doctorn

    public static function adddoctor(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'phone' => 'required|min:9|max:12',
            'email' => 'required|unique:user_table',
            'gender' => 'required',
            'signature' => 'required',
            'dob' => 'required',
            'password' => 'required|min:6'
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
                $file->move(public_path('uploads/doctor'), $filename);
                $userQuery['image'] = $filename;
            }

            if (!empty($request->file('signature'))) {
                $file = $request->file('signature');
                date_default_timezone_set('Asia/Kolkata');

                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('uploads/doctor'), $filename);
                $userQuery['signature'] = $filename;
            }

            $getlastid = Common::getlastid('doctor');

            if (!empty($getlastid->unique_id)) {
                // सिर्फ number निकालकर integer बना दो
                $unique_id = (int) str_replace("TMD-", "", $getlastid->unique_id);

                // अब safely +1 कर सकते हो
                $savedata['unique_id'] = "TMD-" . ($unique_id + 1);
            } else {
                $savedata['unique_id'] = "TMD-100";
            }


            $userQuery['genre'] = 'doctor';
            $query = DB::table('user_table')->insertGetId($userQuery);

            $savedata['doctor_id'] = $query;
            $savedata['gender'] = $request->gender ?? '';
            $savedata['dob'] = $request->dob ?? '';
            // $savedata['website'] = $request->website ?? '';
            // $savedata['personal_chamber'] = $request->personal_chamber ?? '';
            $savedata['degree'] = $request->degree ?? '';
            $savedata['completion_year'] = $request->completion_year ?? '';
            $savedata['registration_no'] = $request->registration_no ?? '';
            $savedata['specialization'] = $request->specialization ?? '';
            $savedata['category_id'] = $request->specialization ?? '';
            $savedata['experience_year'] = $request->experience_year ?? '';
            $savedata['achievement'] = $request->achievement ?? '';
            $savedata['experience_brief'] = $request->experience_brief ?? '';
            $savedata['college'] = $request->college ?? '';

            if (!empty($request->file('document'))) {
                $file = $request->file('document');
                date_default_timezone_set('Asia/Kolkata');

                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('uploads/doctor/document'), $filename);
                $savedata['document'] = $filename;
            }

            $result = DB::table('doctor')->insertGetId($savedata);

            if ($result) {
                return response()->json(['code' => 200, 'message' => 'Doctor Added Successfully']);
            } else {
                return response()->json(['code' => 400, 'message' => 'Something went wrong']);
            }
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'required|email',
            'commission' => 'nullable|numeric',
            'level' => 'nullable|string',
            'gender' => 'nullable',
            'dob' => 'nullable|date',
            'emergency' => 'nullable|string',
            'relation' => 'nullable|string',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            // You can expand validation as needed...
        ]);

        $user = UserTable::findOrFail($request->rowid);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->commission = $request->commission;
        $user->level = $request->level;
        $user->save();

        $doctor = $user->doctor;

        $doctor->gender = $request->gender;
        $doctor->dob = $request->dob;
        $doctor->degree = $request->degree;
        $doctor->college = $request->college;
        $doctor->completion_year = $request->completion_year;
        $doctor->category_id = $request->specialization;
        $doctor->experience_year = $request->experience_year;
        $doctor->achievement = $request->achievement;
        $doctor->experience_brief = $request->experience_brief;
        $doctor->previous_orgnisation = $request->previous_orgnisation;
        $doctor->current_workplace = $request->current_workplace;
        $doctor->emergency = $request->emergency;
        $doctor->relation = $request->relation;
        $doctor->address_line_1 = $request->address_line_1;
        $doctor->address_line_2 = $request->address_line_2;

        // File upload handling
        foreach (
            [
                'adhar_proof',
                'pan_proof',
                'degree_proof',
                'registration_proof',
                'cheque',
                'video_proof'
            ] as $field
        ) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $field === 'video_proof' ? 'uploads/doctor/video_proof' : 'uploads/doctor/' . $field;
                $file->move(public_path($path), $filename);
                $doctor->$field = asset($path . '/' . $filename);
            }
        }

        $doctor->save();

        return response()->json(['code' => 200, 'message' => 'Doctor profile updated successfully']);
    }
}
