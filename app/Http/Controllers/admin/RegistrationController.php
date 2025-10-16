<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public static function index()
    {
        // $data['users'] = User::all();
        // now fetch user from the  user_table
         $data['users'] = Common::getCountCondition('user_table','genre','user','false');

        return view('admin.users.list', $data);
    }


    public static function addnew()
    {
        $data['states'] = Common::stategroupby();
        $data['city'] = Common::citygroupby();
        $data['pincode'] = Common::getpincode();
        return view('admin.users.add',$data);
    }



    public static function store(Request $request)
    {
        $user = $request->except('_token');
        $user['created_at'] = now();
        date_default_timezone_set('Asia/Kolkata');


        if (!empty($request->file('image'))) {
            $file = $request->file('image');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/profile'), $filename);
            $user['image'] = $filename;
        }
        // adharcard
        if (!empty($request->file('adharcard'))) {
            $file = $request->file('adharcard');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/adharcards'), $filename);
            $user['adharcard'] = $filename;
        }
        // pancard
        if (!empty($request->file('pancard'))) {
            $file = $request->file('pancard');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/pancards'), $filename);
            $user['pancard'] = $filename;
        }
        
                $getlastid = Common::getlastid('doctor');
        $unique_id2 = !empty($getlastid->unique_id) 
            ? "FV99-" . ((int) str_replace("FV99-", "", $getlastid->unique_id) + 1) 
            : "FV99-100";
            
            
        // insurance
        if (!empty($request->file('insurance'))) {
            $file = $request->file('insurance');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/insaurance'), $filename);
            $user['insurance'] = $filename;
        }
        // investigation
        if (!empty($request->file('investigation'))) {
            $file = $request->file('investigation');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/investigation'), $filename);
            $user['investigation'] = $filename;
        }
        // others
        if (!empty($request->file('others'))) {
            $file = $request->file('others');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/others'), $filename);
            $user['others'] = $filename;
        }
         $user['role'] = 'user';
         $user['unique_id'] = $unique_id2;

        if (User::insert($user)) {
            return back()->with('message', 'User Registered successfully');
        }
    }




    public static function edit(Request $request)
    {
        $data['item'] = Common::getlistcommon('users', 'id', $request->id);
        $data['states'] = Common::stategroupby();
        $data['city'] = Common::citygroupby();
        $data['pincode'] = Common::getpincode();
        return view('admin.users.edit', $data);
    }

    public static function profile(Request $request)
    {
        $data['item'] = Common::getlistcommon('users', 'id', $request->id);
        return view('admin.users.profile', $data);
    }


    public static function update(Request $request)
    {
        $user = $request->except('_token');
        $user['created_at'] = now();
        date_default_timezone_set('Asia/Kolkata');


        if (!empty($request->file('image'))) {
            $file = $request->file('image');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/profile'), $filename);
            $user['image'] = $filename;
        }
        // adharcard
        if (!empty($request->file('adharcard'))) {
            $file = $request->file('adharcard');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/adharcards'), $filename);
            $user['adharcard'] = $filename;
        }
        // pancard
        if (!empty($request->file('pancard'))) {
            $file = $request->file('pancard');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/pancards'), $filename);
            $user['pancard'] = $filename;
        }
        // insurance
        if (!empty($request->file('insurance'))) {
            $file = $request->file('insurance');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/insaurance'), $filename);
            $user['insurance'] = $filename;
        }
        // investigation
        if (!empty($request->file('investigation'))) {
            $file = $request->file('investigation');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/investigation'), $filename);
            $user['investigation'] = $filename;
        }
        // others
        if (!empty($request->file('others'))) {
            $file = $request->file('others');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('uploads/users/documents/others'), $filename);
            $user['others'] = $filename;
        }


        $result = DB::table('users')->where('id', $request->id)->update($user);

        if ($result) {
            return back()->with('message', 'User Registered successfully');
        }else{
            return back()->with('error', 'Something went wrong');
        }
    }


    public function questions(Request $request){
          $sections = DB::select("SELECT * FROM sections");

    foreach ($sections as &$section) {
        $questions = DB::select("SELECT * FROM questions WHERE section_id = ?", [$section->id]);

        foreach ($questions as &$question) {
            $answers = DB::select("SELECT id, label, text, is_correct FROM answers WHERE question_id = ?", [$question->id]);
            $question->answers = $answers;
        }

        $section->questions = $questions;
    }

    return view('admin.questions.view', compact('sections'));


      

    }


}
