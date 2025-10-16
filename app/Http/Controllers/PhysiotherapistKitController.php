<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PhysiotherapistKitController extends Controller
{
    //
    public static function index()
    {
        $data['physiotherapist_kits'] = DB::table('physiotherapist_kits')->orderBy('id', 'desc')->get();
        // return $kits;
        return view('admin.kits.index', $data);

    }

    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'kit_name'             => 'required|string|max:255',
            'description'          => 'required|string|max:500',
            'benefits'             => 'required|string',
            'price'                => 'required|numeric|min:0',
            'poster_image'         => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'terms_and_conditions' => 'required|string|max:500',

        ]);

        if ($validate->fails()) {
            return response()->json([
                'code'    => 401,
                'message' => $validate->errors()->toArray(),
            ]);
        }

        // Process benefits (convert comma-separated string to array)
        $benefits = array_map('trim', explode(',', $request->benefits));
        $terms = array_map('trim', explode(',', $request->terms_and_conditions));


        $posterImageUrl = null;
        if ($request->hasFile('poster_image')) {
            $file     = $request->file('poster_image');
            $fileName = 'poster_image_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('proof/poster_image'), $fileName);
            $posterImageUrl = url('proof/poster_image/' . $fileName);
        }

        DB::table('physiotherapist_kits')->insert([
            'kit_name'                 => $request->kit_name,
            'description'              => $request->description,
            'benefits'                 => json_encode($benefits),
            'price'                    => $request->price,
            'poster_image'             => $posterImageUrl,
            'terms_and_conditions'     => json_encode($terms),
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);

        return response()->json([
            'code'    => 200,
            'message' => 'Kit added successfully',
        ]);
    }

}
