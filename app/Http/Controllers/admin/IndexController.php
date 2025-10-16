<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\Auth;
use App\Models\Common;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public static function index()
    {
        if (session()->has('session_admin')) {

            return redirect('/'.getlogindetail('role').'/dashboard');
        } else {
            return view('admin/index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function check(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $check = Auth::checkemail($request->email);
        if ($check) {
            $checkpassword = Hash::check($request->password, $check->password);
            if ($checkpassword) {

                $arr = array('id' => $check->id, 'role' => $check->genre, 'name' => $check->name, 'email' => $check->email);
                session()->put('session_admin', $arr);

                return redirect('/'.$check->genre.'/dashboard');
            } else {
                return back()->with('message', 'Wrong Password');
            }
        } else {
            return back()->with('message', 'Email Address not Registerd');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function destroy($id)
    {
        //
    }
}
