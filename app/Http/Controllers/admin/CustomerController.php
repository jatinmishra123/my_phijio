<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    // Get all customers (JSON)
    public static function index()
    {
        $customers = Common::list('users');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'customers' => $customers
        ]);
    }

    // Update customer password
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
        }

        if ($request->new_password !== $request->confirm_password) {
            return response()->json(['code' => 400, 'message' => 'Password & Confirm Password must be same']);
        }

        $savedata['password'] = Hash::make($request->new_password);
        $query = DB::table($request->table)->where('id', $request->rowid)->update($savedata);

        if ($query) {
            return response()->json(['code' => 200, 'message' => 'Password Changed Successfully']);
        } else {
            return response()->json(['code' => 400, 'message' => 'Something went wrong']);
        }
    }
}
