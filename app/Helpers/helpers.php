<?php

use App\Models\Dashboard;
use App\Models\User;


// get login details
if (!function_exists('getlogindetail')) {
    function getlogindetail($string)
    {
        $sess = session('session_admin');
        return $sess[$string] ?? '';
    }
}
if(!function_exists('getall')){
    function getcustomdetails($table){
        Dashboard::getall($table);

    }
}



if (!function_exists('getsidebarmodules')) {
    function getsidebarmodules()
    {
        $det = Dashboard::getsidebardetails();
        $mainarr = array();

        if (!empty($det)) {
            foreach ($det as $value) {
                array_push($mainarr, array('sidebar_id' => $value['sidebar_id'], 'sidebar_name' => $value['sidebar_name']));
            }
        }

        return $mainarr;
    }
}



// get vendor details
if (!function_exists('getvendordetail')) {
    function getvendordetail($string)
    {
        $result = Dashboard::getvendordetail($string, getlogindetail('id'));
        return $result->$string ?? '';
    }
}


if (!function_exists('getwebdetail')) {
    function  getwebdetail($string)
    {
        $result = Dashboard::getwebdetail($string);

        return $result->$string ?? '';
    }
}



if (!function_exists('sendmsg')) {
    function sendmsg($code, $msg)
    {
        return response()->json(['message' => $msg, 'code' => $code,], '200', ['Content-Type' => 'application/json;'], JSON_UNESCAPED_UNICODE);
    }
}



if(!function_exists('getUserSession')){
    function getUserSession($string){
        $sess = session('userSession');
        return $sess[$string]??'';

    }
}

if(!function_exists('getuserinfo')){
    function getuserinfo($string){
        if(getUserSession($string) !=''){
            $email = getUserSession($string);
            $id=  User::where('email',$email)->get();
            return $id[0]['id'];
        }else{
            return  '';
        }

    }
}
