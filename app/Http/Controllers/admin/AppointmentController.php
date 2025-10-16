<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserTable;
use App\Models\Appointment;
use App\Models\Common;
use App\Models\AllotedChamber;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    //
    public static function index(Request $request)
    {
        $slug= $request->slug;
        
        if($slug =='all'){
          $data['timeslots']=  DB::table('timeslots')->leftJoin('user_address','user_address.id','timeslots.address_id')->leftJoin('user_table as doctor','doctor.id','timeslots.user_id')->leftJoin('user_table as users','users.id','timeslots.bookedBy')->where('isBooked',1)->select('timeslots.*','doctor.name as doctor_name','doctor.id as doctorId','users.name as paitent_name','users.phone as patient_phone','user_address.*')->orderBy('timeslots.id','desc')->get();
        }
        
        if($slug =='pending'){
            $data['timeslots']=  DB::table('timeslots')->leftJoin('user_address','user_address.id','timeslots.address_id')->leftJoin('user_table as doctor','doctor.id','timeslots.user_id')->leftJoin('user_table as users','users.id','timeslots.bookedBy')->where('isBooked',1)->where('iscompleted',0)->select('timeslots.*','doctor.name as doctor_name','doctor.id as doctorId','users.name as paitent_name','users.phone as patient_phone','user_address.*')->orderBy('timeslots.id','desc')->get();
            
        }
         if($slug =='completed'){
            $data['timeslots']=  DB::table('timeslots')->leftJoin('user_address','user_address.id','timeslots.address_id')->leftJoin('user_table as doctor','doctor.id','timeslots.user_id')->leftJoin('user_table as users','users.id','timeslots.bookedBy')->where('isBooked',1)->where('iscompleted',1)->select('timeslots.*','doctor.name as doctor_name','doctor.id as doctorId','users.name as paitent_name','users.phone as patient_phone','user_address.*')->orderBy('timeslots.id','desc')->get();
            
        }
        // return $query;
        
        // switch ($request->slug) {
        //     case ('pending'):
        //         $flag = 0;
        //         break;
        //     case ('all'):
        //         $flag = '';
        //         break;
        //     case ('completed'):
        //         $flag = '3';
        //         break;
        // }
        

        // // if the  user is not admin
        // if (getlogindetail('role') != 'admin') {

        //     // here check if the role is  staff so get  its chamber id  first and show data of that chamber
        //     if (getlogindetail('role') == 'staff') {
        //              $chamberid = DB::table('doctor_chamber')->where('doctorId',getlogindetail('id'))->select('chamberId')->get();
        //              $roleid = $chamberid[0]->chamberId;
        //              if ($flag != '') {
        //                 $data['appointment'] = DB::table('appointment')
        //                     ->join('user_table', 'user_table.id', '=', 'appointment.doctor_id')
        //                     ->join('chamber', 'chamber.id', '=', 'appointment.clinic_id')
        //                     ->join('users', 'users.id', '=', 'appointment.user_id')
        
        //                     ->where('appointment.flag', '=', $flag)
        //                     ->where('appointment.clinic_id', '=', $roleid)
        
        
        //                     ->select('appointment.*', 'user_table.name as doctor', 'user_table.phone', 'chamber.name as clinicname', 'users.first_name')
        //                     ->get();
        //             } else {
        //                 $data['appointment'] = DB::table('appointment')
        //                     ->join('user_table', 'user_table.id', '=', 'appointment.doctor_id')
        //                     ->join('chamber', 'chamber.id', '=', 'appointment.clinic_id')
        //                     ->join('users', 'users.id', '=', 'appointment.user_id')
        //                     ->where('appointment.clinic_id', '=', $roleid)
        
        
        
        //                     ->select('appointment.*', 'user_table.name as doctor', 'user_table.phone', 'chamber.name as clinicname', 'users.first_name')
        //                     ->get();
        //             }
                     

        //     }else{
        //         if ($flag != '') {
        //             $data['appointment'] = DB::table('appointment')
        //                 ->join('user_table', 'user_table.id', '=', 'appointment.doctor_id')
        //                 ->join('chamber', 'chamber.id', '=', 'appointment.clinic_id')
        //                 ->join('users', 'users.id', '=', 'appointment.user_id')
    
        //                 ->where('appointment.flag', '=', $flag)
        //                 ->where('appointment.doctor_id', '=', getlogindetail('id'))
    
    
        //                 ->select('appointment.*', 'user_table.name as doctor', 'user_table.phone', 'chamber.name as clinicname', 'users.first_name')
        //                 ->get();
        //         } else {
        //             $data['appointment'] = DB::table('appointment')
        //                 ->join('user_table', 'user_table.id', '=', 'appointment.doctor_id')
        //                 ->join('chamber', 'chamber.id', '=', 'appointment.clinic_id')
        //                 ->join('users', 'users.id', '=', 'appointment.user_id')
        //                 ->where('appointment.doctor_id', '=', getlogindetail('id'))
    
    
    
        //                 ->select('appointment.*', 'user_table.name as doctor', 'user_table.phone', 'chamber.name as clinicname', 'users.first_name')
        //                 ->get();
        //         }


        //     }
           

            
        // } else {
        //     if ($flag != '') {
        //         $data['appointment'] = DB::table('appointment')
        //             ->join('user_table', 'user_table.id', '=', 'appointment.doctor_id')
        //             ->join('chamber', 'chamber.id', '=', 'appointment.clinic_id')
        //             ->join('users', 'users.id', '=', 'appointment.user_id')

        //             ->where('appointment.flag', '=', $flag)


        //             ->select('appointment.*', 'user_table.name as doctor', 'user_table.phone', 'chamber.name as clinicname', 'users.first_name')
        //             ->get();
        //     } else {
        //         $data['appointment'] = DB::table('appointment')
        //             ->join('user_table', 'user_table.id', '=', 'appointment.doctor_id')
        //             ->join('chamber', 'chamber.id', '=', 'appointment.clinic_id')
        //             ->join('users', 'users.id', '=', 'appointment.user_id')



        //             ->select('appointment.*', 'user_table.name as doctor', 'user_table.phone', 'chamber.name as clinicname', 'users.first_name')
        //             ->get();
        //     }
        // }

        return view('admin.appointment.list', $data);
    }


    public static function add()
    {
        $data['users'] = User::all();
        $data['chamber'] = Common::listnamewise('chamber', 'name');
        $data['doctors'] = UserTable::where('genre', 'doctor')->select('name', 'id')->orderBy('id', 'desc')->get();

        return view('admin/appointment/add', $data);
    }

    public static function userinfo(Request $request)
    {
        $info = User::where('first_name', $request->username)->get();

        if (!empty($info[0]->dob)) {
            $new_birth_date = explode('-', $info[0]->dob);
            $year = $new_birth_date[0];
            $month = $new_birth_date[1];
            $day  = $new_birth_date[2];

            date_default_timezone_set('Asia/Kolkata');

            if ($month <= 3) {
                $new_year = date('Y') - $year;
                $new_day = 31 - $day;
                $new_month = 3 - $month;
            } else {
                $new_month = 15 - $month;
                $new_year = date('Y') - $year;
                $new_day = 31 - $day;
            }


            // $age = $new_year . " Years " . $new_month . " Months " . $new_day . " Days";
            $age = $new_year . " Years ";


            return   response()->json(['gender' => $info[0]->gender, 'id' => $info[0]->id, 'dob' => $info[0]->dob, 'mobile' => $info[0]->mobile, 'age' => $age]);
        }
    }

    public function chamberinfo(Request $request)
    {
        $info = Common::getlistcommon('chamber', 'name', $request->chambername);
        return response()->json(['chamberid' => $info->id]);
    }



    public static function doctorinfo(Request $request)
    {
        $info = DB::table('user_table')
            ->join('doctor_chamber', 'user_table.id', '=', 'doctor_chamber.doctorId')
            ->where('user_table.id', $request->userid)
            ->select('user_table.*', 'doctor_chamber.*')
            ->get();
        return response()->json(['id' => $info[0]->id, 'fee' => $info[0]->fee]);
    }



    public static function store(Request $request)
    {
        $savedata = $request->except('_token');
        $getlastid = Common::getlastid('appointment', 'appointment_id');

        if (!empty($getlastid->appointment_id)) {
            $unique_id = str_replace("APT-", "", $getlastid->appointment_id);
            $savedata['appointment_id'] = "APT-" . ($unique_id + 1);
        } else {
            $savedata['appointment_id'] = "APT-100";
        }

        $query = Appointment::insert($savedata);

        if ($query) {
            return response()->json(['code' => '200', 'message' => 'Appointment Created Successfully']);
        } else {
            return response()->json(['code' => '400', 'message' => 'Something went wrong']);
        }
    }




    function alloteddoctor(Request $request)
    {

        $chamberDoctors =  AllotedChamber::with('Doctors')->where('chamberId', $request->chamberid)->get();
        $output = '';
        if (count($chamberDoctors) > 0) {
            $output .= '<option value ="">Select Doctor</option>';
            foreach ($chamberDoctors as $list) {
                $output .= '
                <option value="' . $list->doctors[0]->id . '">' . $list->doctors[0]->name . '</option>';
            }
        } else {
            $output .=  '<option value ="">No Doctor alloted to this clinic</option>';
        }
        return response()->json(['code' => '200', 'data' => $output]);
    }
    
    public function allwithdrawel(Request $request){
         $data['withdrawals'] = DB::table('redeemrequest')->join('user_table as ut','ut.id','redeemrequest.doctor_id')->orderby('redeemrequest.id','desc')->get();
        
        return view('admin.redeem.list',$data);
    }
    
    
    
}
