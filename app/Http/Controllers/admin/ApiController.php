<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserTable;
use App\Models\otpVerify;
use App\Models\PhysiotherapistKit;
use App\Models\OrderDetails;
use App\Models\PlanOrderDetails;
use App\Models\PaymentKitOrder;
use App\Models\PaymentPlanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreKitReviewRequest;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Category;
use App\Models\CategoryLevel;
use App\Models\Common;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;


use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function createaccount(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'full_name' => 'required',
            'phone' => 'required|unique:user_table,phone',
            'email' => 'required|email|unique:user_table,email',
            'password' => 'required',
            'otp' => 'required',
            'role' => 'required|in:doctor,user,nurse',
        ], [
            'role.in' => 'Role can be doctor, user, or nurse',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            $checkExist = OtpVerify::where('phone', $request->phone)->first();

            if (!$checkExist) {
                return response()->json(['code' => 401, 'message' => ['otp' => 'Please send OTP first']], 401);
            }

            if ($checkExist->otp !== $request->otp) {
                return response()->json(['code' => 401, 'message' => ["otp" => 'Wrong OTP']], 401);
            }

            // Mark OTP as verified
            $checkExist->is_verify = 1;
            $checkExist->save();

            // Generate Unique ID for Doctors
            $getlastid = Common::getlastid('doctor');
            $unique_id2 = !empty($getlastid->unique_id)
                ? "FV99-" . ((int) str_replace("FV99-", "", $getlastid->unique_id) + 1)
                : "FV99-100";

            // Prepare Data for User Creation
            $datatobeSaved = [
                'name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'genre' => $request->role,
            ];

            if ($request->role !== 'user') {
                $datatobeSaved['flag'] = 1;
            }

            // Create User
            $user = UserTable::create($datatobeSaved);
            $insertedUserId = $user->id;

            // Store Raw Password (for reference)
            DB::table('user_table')->where('id', $insertedUserId)
                ->update(['password_text' => $request->password, 'updated_at' => now()]);

            // Assign Unique ID if Role is Doctor or Nurse
            if ($request->role === 'doctor' || $request->role === 'nurse') {
                $checkDoctor = DB::table('doctor')->where('doctor_id', $insertedUserId)->first();

                if ($checkDoctor) {
                    DB::table('doctor')->where('doctor_id', $insertedUserId)
                        ->update(['unique_id' => $unique_id2]);
                } else {
                    DB::table('doctor')->insert(['doctor_id' => $insertedUserId, 'unique_id' => $unique_id2]);
                }
            }

            // Authenticate User and Generate JWT Token
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            $customClaims = ['exp' => now()->addYear()->timestamp]; // Token valid for 1 year

            if (!$token = JWTAuth::attempt($credentials, $customClaims)) {
                return response()->json(['code' => 401, 'message' => 'Could not create token'], 401);
            }

            return response()->json([
                'code' => 200,
                'message' => 'User Created Successfully',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function getbanners(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'role' => 'required|in:doctor,user',
        ], [
            'role.in' => 'Role can be doctor, user, or nurse',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        $categories = DB::table('banner')->where('category_for', $request->role)->get();

        $data = $categories->map(function ($listing) {
            return [
                'id' => $listing->id,
                'category_for' => $listing->category_for,
                'banner' => url('uploads/category/' . $listing->banner),
            ];
        });

        return response()->json([
            'code' => 200,
            'message' => 'Banner Found Successfully',
            'banner' => $data,
        ], 200);
    }

    public function checkOtpVerifyNew(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'phone' => 'required|unique:user_table,phone',
            'email' => 'required|email|unique:user_table,email',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        } else {
            try {

                // check if the  phone or email exist

                //  generate otp
                // $otp =  rand(111111,999999);

                $otp = 123456;
                // update user  with otp
                $checkExist = otpVerify::where('phone', $request->phone)->first();
                // find the existing one and update otp
                if ($checkExist) {
                    // Update existing record
                    $checkExist->otp = $otp;
                    $checkExist->phone = $request->phone;
                    $checkExist->created_at = now();
                    $checkExist->save();
                    $query = true;
                } else {
                    // Insert new record
                    $query = otpVerify::create([
                        'phone' => $request->phone,
                        'otp' => $otp,
                        'created_at' => now(),
                    ]);
                }

                if ($query) {
                    return response()->json(['code' => 200, 'message' => 'OTP Sent  Successfully'], 200);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Something went wrong'], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
            }
        }
    }

    public function getUser(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();

            // Fetch doctor profile with user details
            $userWithDoctor = DB::table('user_table')
                ->leftJoin('doctor', 'user_table.id', '=', 'doctor.doctor_id')
                ->leftJoin('category', 'doctor.category_id', '=', 'category.id')
                ->where('user_table.id', $user->id)
                ->select(
                    'user_table.id',
                    'user_table.name',
                    'user_table.email',
                    'user_table.phone',
                    'user_table.genre',
                    'user_table.image',

                    'doctor.dob',
                    'doctor.gender',
                    'doctor.address_line_1',
                    'doctor.address_line_2',
                    'doctor.country',
                    'doctor.state',
                    'doctor.city',
                    'doctor.zipcode',
                    'doctor.description',

                    'doctor.degree',
                    'doctor.college',
                    'doctor.completion_year',
                    'doctor.category_id',
                    'category.category_name',

                    'doctor.experience_year',
                    'doctor.previous_orgnisation',
                    'doctor.area_of_expertise',
                    'doctor.current_workplace',

                    'doctor.bank_name',
                    'doctor.holder_name',
                    'doctor.account_number',
                    'doctor.ifsc_code',
                    'doctor.upi_id',
                    'doctor.cheque',

                    'doctor.employment_type',
                    'doctor.willing_to_travel',
                    'doctor.emergency',
                    'doctor.relation',
                    'doctor.referral_code',

                    'doctor.adhar_proof',
                    'doctor.pan_proof',
                    'doctor.degree_proof',
                    'doctor.registration_proof',
                    'doctor.signature',
                    'doctor.image',
                    'doctor.video_proof',

                    'doctor.unique_id'
                )
                ->first();

            if (!$userWithDoctor) {
                return response()->json([
                    'code' => 404,
                    'message' => 'User not found'
                ], 404);
            }

            // Build full URLs for all image/file fields
            # $fileFields = [
            #     'user_image' => 'uploads/doctor',
            #     'cheque' => 'uploads/doctor/cheque',
            #     'adhar_proof' => 'uploads/doctor/adharcard',
            #     'pan_proof' => 'uploads/doctor/pancard',
            #     'degree_proof' => 'uploads/doctor/degree',
            #     'registration_proof' => 'uploads/doctor/registrations',
            #     'signature' => 'uploads/doctor/signature',
            #     'video_proof' => 'uploads/doctor/video_proof',
            # ];

            # foreach ($fileFields as $field => $path) {
            #     if (!empty($userWithDoctor->$field)) {
            #         $userWithDoctor->$field = url($path . '/' . basename($userWithDoctor->$field));
            #     } else {
            #         $userWithDoctor->$field = null;
            #     }
            # }

            // Override main image field

            // Load multi-categories if genre = doctor or nurse
            if (in_array($user->genre, ['doctor', 'nurse'])) {
                $userWithDoctor->categories = DB::table('doctor_category')
                    ->where('doctor_id', $user->id)
                    ->pluck('category_id');
            } else {
                $userWithDoctor->categories = [];
            }

            return response()->json([
                'code' => 200,
                'message' => 'User profile fetched successfully',
                'user' => $userWithDoctor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }


    public function otplogin(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'role' => 'required|in:user,doctor'
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        } else {
            try {
                // check the number for the role exist or not
                $user = UserTable::where('phone', $request->phone)->where('genre', $request->role)->first();


                if (empty($user)) {
                    return response()->json(['code' => 401, 'message' => 'User not exist!'], 401);
                }


                //  generate otp
                // $otp =  rand(111111,999999);

                $otp = 123456;
                // update user  with otp
                $checkExist = otpVerify::where('phone', $request->phone)->first();
                // find the existing one and update otp
                if ($checkExist) {
                    // Update existing record
                    $checkExist->otp = $otp;
                    $checkExist->phone = $request->phone;
                    $checkExist->created_at = now();
                    $checkExist->save();
                    $query = true;
                } else {
                    // Insert new record
                    $query = otpVerify::create([
                        'phone' => $request->phone,
                        'otp' => $otp,
                        'created_at' => now(),
                    ]);
                }

                if ($query) {
                    return response()->json(['code' => 200, 'message' => 'OTP Sent  Successfully'], 200);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Something went wrong'], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
            }
        }
    }

    public function login(Request $request)
    {
        // Validate input
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            // Check if the user exists using phone or email
            $user = UserTable::whereIn('genre', ['user', 'doctor'])->where('phone', $request->phone)
                ->orWhere('email', $request->phone)
                ->first();

            if (!$user) {
                return response()->json(['code' => 401, 'message' => ['email' => ['Phone not registered']]], 401);
            }

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['code' => 401, 'message' => 'Wrong Credentials'], 401);
            }

            // Generate JWT token with 1-year expiry
            $credentials = ['email' => $user->email, 'password' => $request->password];
            $customClaims = ['exp' => now()->addYear()->timestamp];

            if (!$token = JWTAuth::attempt($credentials, $customClaims)) {
                return response()->json(['code' => 401, 'message' => 'Could not create token'], 401);
            }

            // Store plain password (if needed, but not recommended for security reasons)
            $user->password_text = $request->password;
            $user->save();

            // Get doctor's working hours if user is a doctor
            $doctorData = DB::table('doctor')->where('doctor_id', $user->id)->first();
            $user->workinghours = $doctorData->workinghours ?? '';

            // Set profile image URL
            $user->image = !empty($user->image) ? url('uploads/doctor/' . $user->image) : '';

            return response()->json([
                'code' => 200,
                'message' => 'User Found Successfully',
                'data' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    // public function completeprofile(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'doctor_id' => 'required',
    //         'dob' => 'required',
    //         'fees' => 'required',
    //         'website' => 'nullable|url',
    //         'degree' => 'required|array',
    //         'degree.*' => 'string',
    //         'location' => 'required',
    //         'specialization' => 'required|array',  // Accept specialization as an array
    //         'specialization.*' => 'numeric',  // Each specialization must be a valid category ID
    //         'experience_year' => 'required|numeric',
    //         'achievement' => 'required',
    //         'experience_brief' => 'nullable|string',
    //         'college' => 'nullable|string',
    //         'gender' => 'required|string',
    //         'signature' => 'nullable',
    //         'description' => 'nullable'
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
    //     }

    //     try {
    //         // **Find the existing doctor profile**
    //         $doctor = UserTable::where('id', $request->doctor_id)->first();
    //         if (!$doctor) {
    //             return response()->json(['code' => 404, 'message' => 'Doctor not found'], 404);
    //         }

    //         $updateData = [];

    //         // **Handle Degree (Stored as JSON in doctor table)**
    //         $updateData['degree'] = json_encode($request->degree);
    //         $updateData['achievement'] = json_encode($request->achievement);
    //         $updateData['location'] = json_encode($request->location);
    //         $updateData['experience_year'] = $request->experience_year;
    //         if($request->has('workinghours')){
    //             $updateData['workinghours'] =  $request->workinghours;
    //         }
    //         if($request->has('dob')){
    //             $updateData['dob'] =  $request->dob;
    //         }

    //         // **Handle File Uploads (Image, Signature, Documents)**
    //         $uploadFields = ['image', 'signature', 'document'];
    //         $imageArr = [];

    //         foreach ($uploadFields as $field) {
    //             if ($request->has($field)) {
    //                 $fileDataList = $request->input($field);

    //                 if ($field === 'document' && is_array($fileDataList)) {
    //                     $docFilenames = [];

    //                         foreach ($fileDataList as $fileData) {
    //                             // Check if it's a Base64 encoded image
    //                             if (preg_match('/^data:image\/(\w+);base64,/', $fileData, $matches)) {
    //                                 $imageType = $matches[1];
    //                                 $fileData = substr($fileData, strpos($fileData, ',') + 1);
    //                                 $fileData = base64_decode($fileData);

    //                                 if ($fileData === false) {
    //                                     return response()->json(['code' => 400, 'message' => 'Invalid image format'], 400);
    //                                 }

    //                                 $filename = date('YmdHi') . uniqid() . '.' . $imageType;
    //                                 $filePath = public_path('uploads/doctor/' . $filename);

    //                                 file_put_contents($filePath, $fileData);
    //                                 $docFilenames[] = url('uploads/doctor/' . $filename); // Convert to URL
    //                             } elseif (filter_var($fileData, FILTER_VALIDATE_URL)) {
    //                                 // If it's already a URL, just store it in the array
    //                                 $docFilenames[] = str_replace('http://', 'https://', $fileData); // Ensure HTTPS
    //                             } else {
    //                                 return response()->json(['code' => 400, 'message' => 'Invalid file format'], 400);
    //                             }
    //                         }

    //                         // Convert the filenames array to JSON
    //                         $imageArr[$field] = json_encode($docFilenames);
    //                 } else {
    //                     if (preg_match('/^data:image\/(\w+);base64,/', $fileDataList, $matches)) {
    //                         $imageType = $matches[1];
    //                         $fileDataList = substr($fileDataList, strpos($fileDataList, ',') + 1);
    //                         $fileDataList = base64_decode($fileDataList);

    //                         if ($fileDataList === false) {
    //                             return response()->json(['code' => 400, 'message' => 'Invalid image format'], 400);
    //                         }

    //                         $filename = date('YmdHi') . uniqid() . '.' . $imageType;
    //                         $filePath = public_path('uploads/doctor/' . $filename);

    //                         file_put_contents($filePath, $fileDataList);
    //                         $imageArr[$field] = $filename;
    //                     } else {
    //                         return response()->json(['code' => 400, 'message' => 'Invalid Base64 string'], 400);
    //                     }
    //                 }
    //             }
    //         }

    //         // **Merge Upload Data**
    //         $updateData = array_merge($updateData, $imageArr);
    //         $updateData['doctor_id'] = $request->doctor_id;
    //         $updateData['fees'] = $request->fees;
    //         $updateData['gender'] = $request->gender;
    //         $updateData['description'] = $request->description;

    //         // **Handle Unique ID**
    //         $getlastid = Common::getlastid('doctor');
    //         if (!empty($getlastid->unique_id)) {
    //             $unique_id = str_replace("FV99-", "", $getlastid->unique_id);
    //             $updateData['unique_id'] = "FV99-" . (intval($unique_id) + 1);
    //         } else {
    //             $updateData['unique_id'] = "FV99-100";
    //         }

    //         // **Update or Insert Doctor Profile**
    //         DB::table('doctor')->updateOrInsert(
    //             ['doctor_id' => $request->doctor_id],
    //             array_merge($updateData, ['updated_at' => now()])
    //         );

    //         // **Handle Specializations in `doctor_category` Table**
    //         DB::table('doctor_category')->where('doctor_id', $request->doctor_id)->delete();

    //         $specializationData = [];
    //         foreach ($request->specialization as $categoryId) {
    //             $specializationData[] = [
    //                 'doctor_id' => $request->doctor_id,
    //                 'category_id' => $categoryId,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ];
    //         }

    //         DB::table('doctor_category')->insert($specializationData);

    //         // **Fetch Updated Doctor Details**
    //         $doctor = DB::table('user_table')
    //             ->leftJoin('doctor', 'doctor.doctor_id', 'user_table.id')
    //             ->where('user_table.id', $request->doctor_id)
    //             ->first();

    //         $doctor->degree = json_decode($doctor->degree, true);
    //         $doctor->specialization = DB::table('doctor_category')
    //             ->where('doctor_id', $request->doctor_id)
    //             ->pluck('category_id')
    //             ->toArray();

    //         // **JWT Authentication**
    //         $credentials = [
    //             'email' => $doctor->email,
    //             'password' => $doctor->password_text ?? $request->password
    //         ];

    //         $customClaims = ['exp' => now()->addYear()->timestamp];

    //         if (!$token = JWTAuth::attempt($credentials, $customClaims)) {
    //             return response()->json(['code' => 401, 'message' => 'Could not create token'], 401);
    //         }

    //         if (!empty($doctor->image)) {
    //             $doctor->image = url('uploads/doctor/' . $doctor->image);
    //         }

    //         return response()->json([
    //             'code' => 200,
    //             'message' => 'Doctor Profile Updated Successfully',
    //             'data' => $doctor,
    //             'token' => $token
    //         ], 200);

    //     } catch (\Exception $e) {
    //             \Log::error('Image upload failed: ' . $e->getMessage());

    //         return response()->json(['code' => 500, 'message' => 'Error: ' . $e->getMessage()], 500);
    //     }
    // }

    // public function completeprofile(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    // 'doctor_id'            => 'required',
    // 'dob'                  => 'required',
    // 'fees'                 => 'required',
    // 'website'              => 'nullable|url',
    // 'degree'               => 'required|array',
    // 'degree.*'             => 'string',
    // 'location'             => 'required',
    // 'specialization'       => 'required|array',
    // 'specialization.*'     => 'numeric',
    // 'experience_year'      => 'required|numeric',
    // 'achievement'          => 'required',
    // 'experience_brief'     => 'nullable|string',
    // 'country'              => 'nullable|string',
    // 'state'                => 'nullable|string',
    // 'zipcode'              => 'nullable|string',
    // 'city'                 => 'nullable|string',
    // 'previous_orgnisation' => 'nullable|string',
    // 'current_workplace'    => 'nullable|string',
    // 'area_of_expertise'    => 'nullable|string',
    // 'college'              => 'nullable|string',
    // 'completion_year'      => 'nullable|string',
    // 'bank_name'            => 'nullable|string',
    // 'holder_name'          => 'nullable|string',
    // 'account_number'       => 'nullable|string',
    // 'ifsc_code'            => 'nullable|string',
    // 'upi_id'               => 'nullable|string',

    // 'gender'               => 'required|string',
    // 'image'                => 'nullable',
    // 'signature'            => 'nullable|image',
    // // 'document.*' => 'nullable|file',
    // 'description'          => 'nullable',
    // 'employment_type'      => 'nullable',
    // 'willing_to_travel'    => 'nullable',
    // 'emergency'            => 'nullable',
    // 'relation'             => 'nullable',
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
    //     }
    //     // return $request->all();

    //     try {
    //         $doctor = UserTable::where('id', $request->doctor_id)->first();
    //         if (! $doctor) {
    //             return response()->json(['code' => 404, 'message' => 'Doctor not found'], 404);
    //         }

    //         $updateData = [];

    //         $updateData['degree']               = json_encode($request->degree);
    //         $updateData['achievement']          = json_encode($request->achievement);
    //         $updateData['location']             = json_encode($request->location);
    //         $updateData['experience_year']      = $request->experience_year;
    //         $updateData['dob']                  = $request->dob;
    //         $updateData['fees']                 = $request->fees;
    //         $updateData['college']              = $request->college;
    //         $updateData['completion_year']      = $request->completion_year;
    //         $updateData['previous_orgnisation'] = $request->previous_orgnisation;
    //         $updateData['area_of_expertise']    = $request->area_of_expertise;
    //         $updateData['bank_name']            = $request->bank_name;
    //         $updateData['account_number']       = $request->account_number;
    //         $updateData['ifsc_code']            = $request->ifsc_code;
    //         $updateData['upi_id']               = $request->upi_id;
    //         $updateData['current_workplace']    = $request->current_workplace;
    //         $updateData['employment_type']      = $request->employment_type;
    //         $updateData['willing_to_travel']    = $request->willing_to_travel;
    //         $updateData['emergency']            = $request->emergency;
    //         $updateData['relation']             = $request->relation;
    //         $updateData['gender']               = $request->gender;
    //         $updateData['country']              = $request->country;
    //         $updateData['state']                = $request->state;
    //         $updateData['city']                 = $request->city;
    //         $updateData['zipcode']              = $request->zipcode;
    //         $updateData['description']          = $request->description;

    //         if ($request->has('workinghours')) {
    //             $updateData['workinghours'] = $request->workinghours;
    //         }

    //         // File uploads
    //         if ($request->hasFile('image')) {
    //             $imageFile = $request->file('image');
    //             $filename  = time() . uniqid() . '.' . $imageFile->getClientOriginalExtension();
    //             $imageFile->move(public_path('uploads/doctor'), $filename);
    //             //  return $filename;
    //             $updateData['image'] = url('uploads/doctor/' . $filename);
    //             $user['image']       = $filename;
    //             $user['updated_at']  = now();

    //             DB::table('user_table')->where('id', $request->doctor_id)->update($user);

    //         }

    //         if ($request->hasFile('signature')) {
    //             $signatureFile = $request->file('signature');
    //             $filename      = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
    //             $signatureFile->move(public_path('uploads/doctor'), $filename);
    //             $updateData['signature'] = url('uploads/doctor/' . $filename);
    //         }
    //         if ($request->hasFile('cheque')) {
    //             $signatureFile = $request->file('cheque');
    //             $filename      = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
    //             $signatureFile->move(public_path('uploads/doctor'), $filename);
    //             $updateData['cheque'] = url('uploads/doctor/' . $filename);
    //         }

    //         $docFilenames = [];
    //         // get the previouis  document first

    //         if ($request->has('docs_url')) {
    //             if (count($request->docs_url) > 0) {
    //                 foreach ($request->docs_url as $list) {
    //                     $docFilenames[] = $list;
    //                 }
    //             }
    //         }

    //         if ($request->hasFile('document')) {

    //             foreach ($request->file('document') as $doc) {

    //                 $filename = time() . uniqid() . '.' . $doc->getClientOriginalExtension();
    //                 $doc->move(public_path('uploads/doctor'), $filename);
    //                 $docFilenames[] = url('uploads/doctor/' . $filename);

    //             }
    //             // return $docFilenames;
    //             $updateData['document'] = json_encode($docFilenames);
    //         }

    //         // Unique ID handling
    //         $getlastid = Common::getlastid('doctor');
    //         if (! empty($getlastid->unique_id)) {
    //             $unique_id               = str_replace("FV99-", "", $getlastid->unique_id);
    //             $updateData['unique_id'] = "FV99-" . (intval($unique_id) + 1);
    //         } else {
    //             $updateData['unique_id'] = "FV99-100";
    //         }

    //         DB::table('doctor')->updateOrInsert(
    //             ['doctor_id' => $request->doctor_id],
    //             array_merge($updateData, ['updated_at' => now()])
    //         );

    //         // Update specialization
    //         DB::table('doctor_category')->where('doctor_id', $request->doctor_id)->delete();

    //         $specializationData = [];

    //         foreach ($request->specialization as $categoryId) {
    //             $specializationData[] = [
    //                 'doctor_id'   => $request->doctor_id,
    //                 'category_id' => $categoryId,
    //                 'created_at'  => now(),
    //                 'updated_at'  => now(),
    //             ];
    //         }

    //         DB::table('doctor_category')->insert($specializationData);

    //         // Fetch updated doctor
    //         $doctor = DB::table('user_table')
    //             ->leftJoin('doctor', 'doctor.doctor_id', 'user_table.id')
    //             ->where('user_table.id', $request->doctor_id)
    //             ->first();

    //         $doctor->degree         = json_decode($doctor->degree, true);
    //         $doctor->specialization = DB::table('doctor_category')
    //             ->where('doctor_id', $request->doctor_id)
    //             ->pluck('category_id')
    //             ->toArray();

    //         // if (!empty($doctor->image)) {
    //         //     $doctor->image = url('uploads/doctor/' . $doctor->image);
    //         // }

    //         // JWT Token
    //         $credentials = [
    //             'email'    => $doctor->email,
    //             'password' => $doctor->password_text ?? $request->password,
    //         ];

    //         $customClaims = ['exp' => now()->addYear()->timestamp];

    //         if (! $token = JWTAuth::attempt($credentials, $customClaims)) {
    //             return response()->json(['code' => 401, 'message' => 'Could not create token'], 401);
    //         }

    //         return response()->json([
    //             'code'    => 200,
    //             'message' => 'Doctor Profile Updated Successfully',
    //             'data'    => $doctor,
    //             'token'   => $token,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         \Log::error('Profile update failed: ' . $e->getMessage());
    //         return response()->json(['code' => 500, 'message' => 'Error: ' . $e->getMessage()], 500);
    //     }
    // }

    public function completeprofile(Request $request)
    {
        $validate = Validator::make($request->all(), [

            'doctor_id' => 'required',
            'dob' => 'required',
            'gender' => 'required|string',
            'address_line_1' => 'nullable',
            'address_line_2' => 'nullable',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'city' => 'nullable|string',
            'degree' => 'required|array',
            'specialization.*' => 'numeric',
            'college' => 'nullable|string',
            'completion_year' => 'nullable|string',
            // 'specialization'              => 'required|array',
            'experience_year' => 'required|numeric',
            'previous_orgnisation' => 'nullable|string',
            'area_of_expertise' => 'nullable|string',
            'current_workplace' => 'nullable|string',
            'license_number' => 'nullable',
            'additional_certificate' => 'nullable',
            'identity_type' => 'nullable',
            'license_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'certificate_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'identity_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'degree_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'council_registration_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'experience_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'police_verification_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bank_name' => 'nullable|string',
            'holder_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'upi_id' => 'nullable|string',
            'cheque' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'employment_type' => 'nullable',
            'willing_to_travel' => 'nullable',
            'emergency' => 'nullable',
            'relation' => 'nullable',
            'referral_code' => 'nullable',
            'video_proof' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:51200',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'level' => 'required|string',


            'fees' => 'required',
            'website' => 'nullable|url',
            'degree.*' => 'string',
            'location' => 'required',
            'achievement' => 'required',
            'experience_brief' => 'nullable|string',
            'current_workplace' => 'nullable|string',
            'image' => 'nullable',

            'document.*' => 'nullable|file',
            'description' => 'nullable',


        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }
        // return $request->all();

        try {
            $doctor = UserTable::where('id', $request->doctor_id)->first();
            if (!$doctor) {
                return response()->json(['code' => 404, 'message' => 'Doctor not found'], 404);
            }

            $updateData = [];

            $updateData['degree'] = json_encode($request->degree);
            $updateData['achievement'] = json_encode($request->achievement);
            $updateData['location'] = json_encode($request->location);
            $updateData['experience_year'] = $request->experience_year;
            $updateData['dob'] = $request->dob;
            $updateData['fees'] = $request->fees;
            $updateData['college'] = $request->college;
            $updateData['completion_year'] = $request->completion_year;
            $updateData['previous_orgnisation'] = $request->previous_orgnisation;
            $updateData['area_of_expertise'] = $request->area_of_expertise;
            $updateData['bank_name'] = $request->bank_name;
            $updateData['account_number'] = $request->account_number;
            $updateData['ifsc_code'] = $request->ifsc_code;
            $updateData['upi_id'] = $request->upi_id;
            $updateData['current_workplace'] = $request->current_workplace;
            $updateData['employment_type'] = $request->employment_type;
            $updateData['willing_to_travel'] = $request->willing_to_travel;
            $updateData['emergency'] = $request->emergency;
            $updateData['relation'] = $request->relation;
            $updateData['gender'] = $request->gender;
            $updateData['country'] = $request->country;
            $updateData['state'] = $request->state;
            $updateData['city'] = $request->city;
            $updateData['zipcode'] = $request->zipcode;
            $updateData['description'] = $request->description;

            $updateData['address_line_1'] = $request->address_line_1;
            $updateData['address_line_2'] = $request->address_line_2;
            $updateData['license_number'] = $request->license_number;
            $updateData['additional_certificate'] = $request->additional_certificate;
            $updateData['identity_type'] = $request->identity_type;
            $updateData['license_proof'] = $request->license_proof;
            $updateData['certificate_proof'] = $request->certificate_proof;
            $updateData['identity_proof'] = $request->identity_proof;
            $updateData['degree_proof'] = $request->degree_proof;
            $updateData['council_registration_proof'] = $request->council_registration_proof;
            $updateData['experience_proof'] = $request->experience_proof;
            $updateData['police_verification_proof'] = $request->police_verification_proof;
            $updateData['cheque'] = $request->cheque;
            $updateData['referral_code'] = $request->referral_code;
            $updateData['video_proof'] = $request->video_proof;
            $updateData['specialization'] = $request->specialization;


            $updateData['holder_name'] = $request->holder_name;


            $updateData['level'] = $request->level;


            if ($request->has('workinghours')) {
                $updateData['workinghours'] = $request->workinghours;
            }

            // File uploads
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $filename = time() . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('uploads/doctor'), $filename);
                //  return $filename;
                $updateData['image'] = url('uploads/doctor/' . $filename);
                $user['image'] = $filename;
                $user['updated_at'] = now();

                DB::table('user_table')->where('id', $request->doctor_id)->update($user);
            }

            if ($request->hasFile('signature')) {
                $signatureFile = $request->file('signature');
                $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
                $signatureFile->move(public_path('uploads/doctor'), $filename);
                $updateData['signature'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('cheque')) {
                $signatureFile = $request->file('cheque');
                $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
                $signatureFile->move(public_path('uploads/doctor'), $filename);
                $updateData['cheque'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('license_proof')) {
                $file = $request->file('license_proof');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/doctor'), $filename);
                $updateData['license_proof'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('certificate_proof')) {
                $file = $request->file('certificate_proof');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/doctor'), $filename);
                $updateData['certificate_proof'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('identity_proof')) {
                $file = $request->file('identity_proof');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/doctor'), $filename);
                $updateData['identity_proof'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('degree_proof')) {
                $file = $request->file('degree_proof');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/doctor'), $filename);
                $updateData['degree_proof'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('council_registration_proof')) {
                $file = $request->file('council_registration_proof');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/doctor'), $filename);
                $updateData['council_registration_proof'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('experience_proof')) {
                $file = $request->file('experience_proof');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/doctor'), $filename);
                $updateData['experience_proof'] = url('uploads/doctor/' . $filename);
            }
            if ($request->hasFile('police_verification_proof')) {
                $file = $request->file('police_verification_proof');
                $filename = time() . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/doctor'), $filename);
                $updateData['police_verification_proof'] = url('uploads/doctor/' . $filename);
            }

            $docFilenames = [];
            // get the previouis  document first

            if ($request->has('docs_url')) {
                if (count($request->docs_url) > 0) {
                    foreach ($request->docs_url as $list) {
                        $docFilenames[] = $list;
                    }
                }
            }

            if ($request->hasFile('document')) {

                foreach ($request->file('document') as $doc) {

                    $filename = time() . uniqid() . '.' . $doc->getClientOriginalExtension();
                    $doc->move(public_path('uploads/doctor'), $filename);
                    $docFilenames[] = url('uploads/doctor/' . $filename);
                }
                // return $docFilenames;
                $updateData['document'] = json_encode($docFilenames);
            }

            // Unique ID handling
            $getlastid = Common::getlastid('doctor');
            if (!empty($getlastid->unique_id)) {
                $unique_id = str_replace("FV99-", "", $getlastid->unique_id);
                $updateData['unique_id'] = "FV99-" . (intval($unique_id) + 1);
            } else {
                $updateData['unique_id'] = "FV99-100";
            }

            DB::table('doctor')->updateOrInsert(
                ['doctor_id' => $request->doctor_id],
                array_merge($updateData, ['updated_at' => now()])
            );

            // Update specialization
            DB::table('doctor_category')->where('doctor_id', $request->doctor_id)->delete();

            $specializationData = [];

            foreach ($request->specialization as $categoryId) {
                $specializationData[] = [
                    'doctor_id' => $request->doctor_id,
                    'category_id' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('doctor_category')->insert($specializationData);

            // Fetch updated doctor
            $doctor = DB::table('user_table')
                ->leftJoin('doctor', 'doctor.doctor_id', 'user_table.id')
                ->where('user_table.id', $request->doctor_id)
                ->first();

            $doctor->degree = json_decode($doctor->degree, true);
            $doctor->specialization = DB::table('doctor_category')
                ->where('doctor_id', $request->doctor_id)
                ->pluck('category_id')
                ->toArray();

            // if (!empty($doctor->image)) {
            //     $doctor->image = url('uploads/doctor/' . $doctor->image);
            // }

            // JWT Token
            $credentials = [
                'email' => $doctor->email,
                'password' => $doctor->password_text ?? $request->password,
            ];

            $customClaims = ['exp' => now()->addYear()->timestamp];

            if (!$token = JWTAuth::attempt($credentials, $customClaims)) {
                return response()->json(['code' => 401, 'message' => 'Could not create token'], 401);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Doctor Profile Updated Successfully',
                'data' => $doctor,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
            return response()->json(['code' => 500, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function verifyotp(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {

            $userTableOtp = DB::table('user_table')->where('phone', $request->phone)->first();

            if ($userTableOtp) { // Ensure $userTableOtp is not null
                $ckexist = OtpVerify::where('phone', $request->phone)->first();

                if ($ckexist) {

                    OtpVerify::where('phone', $request->phone)->update([
                        'email' => $userTableOtp->email,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]);
                }
            }

            // Check if phone or email exists
            $checkExist = OtpVerify::where('phone', $request->phone)
                ->orWhere('email', $request->phone)
                ->first();

            if (empty($checkExist)) {
                return response()->json(['code' => 401, 'message' => ['otp' => 'Please send OTP first']], 401);
            }

            if ($checkExist->otp == $request->otp) {
                // Mark OTP as verified
                $checkExist->is_verify = 1;
                $checkExist->save();

                $doctors = DB::table('user_table')->leftjoin('doctor', 'doctor.doctor_id', 'user_table.id')->where('user_table.id', $userTableOtp->id)->first();
                $credentials = [
                    'email' => $doctors->email,
                    'password' => $doctors->password_text,
                ];
                $customClaims = [
                    'exp' => \Carbon\Carbon::now('UTC')->addYear()->getTimestamp(),
                ];

                if (!$token = JWTAuth::attempt($credentials, $customClaims)) {
                    return response()->json(['code' => 401, 'message' => 'Could not create token'], 401);
                }

                return response()->json([
                    'code' => 200,
                    'message' => 'OTP verified  successfully!',
                    'totan' => $token,
                    'data' => $doctors,

                ], 200);
            } else {
                return response()->json(['code' => 401, 'message' => ["otp" => 'Wrong OTP']], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }
        try {

            $password = Hash::make($request->password);
            // Create User
            $user = UserTable::where('phone', $request->phone)->update(['password' => $password, 'updated_at' => now()]);
            if ($user) {
                return response()->json(['code' => 200, 'message' => 'Password Updated Successfully!'], 200);
            } else {
                return response()->json(['code' => 500, 'message' => ["password" => 'Something went wrong']], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function createSlot(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $validate = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'timeslots' => 'required|array',
            'timeslots.*.date' => 'required|string', // Date is stored as a string
            'timeslots.*.start_time' => 'required|string',
            'timeslots.*.end_time' => 'required|string',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            foreach ($request->timeslots as $timeslot) {
                DB::table('timeslots')->insert([
                    'user_id' => $request->doctor_id,
                    'date' => $timeslot['date'],
                    'start_time' => $timeslot['start_time'],
                    'end_time' => $timeslot['end_time'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'bookedBy' => $user->id,
                ]);
            }

            return response()->json(['code' => 200, 'message' => 'Timeslots created successfully']);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSlot(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // Validate the incoming request
        $validate = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'timeslots' => 'required|array',
            'timeslots.*.id' => 'required|exists:timeslots,id', // The id of the timeslot should exist in the database
            'timeslots.*.date' => 'required|string',              // Date is stored as a string
            'timeslots.*.start_time' => 'required|string',
            'timeslots.*.end_time' => 'required|string',
            'isBooked' => 'nullable|boolean', // `isBooked` should be a boolean if provided
            'bookedBy' => 'nullable',         // `bookedBy` should be a string if provided
        ]);

        // If validation fails, return the error messages
        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            // Loop through each timeslot and update
            foreach ($request->timeslots as $timeslot) {
                $updateData = [
                    'date' => $timeslot['date'],
                    'start_time' => $timeslot['start_time'],
                    'end_time' => $timeslot['end_time'],
                    'updated_at' => now(),
                ];

                // Add optional fields to the update if provided
                if (isset($request->isBooked)) {
                    $updateData['is_booked'] = $request->isBooked;
                }

                if (isset($request->bookedBy)) {
                    $updateData['booked_by'] = $request->bookedBy;
                }

                // Find the timeslot by its ID and update it
                DB::table('timeslots')
                    ->where('id', $timeslot['id'])
                    ->where('user_id', $request->doctor_id) // Ensure it belongs to the correct doctor
                    ->update($updateData);
            }

            return response()->json(['code' => 200, 'message' => 'Timeslots updated successfully']);
        } catch (\Exception $e) {
            // In case of an exception, return an error message
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteSlot(Request $request)
    {
        // Validate the doctor_id and timeslot id
        $validate = Validator::make($request->all(), [
            'slot_id' => 'required', // Ensure doctor exists
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            // Find the timeslot and delete it if it belongs to the doctor
            $id = $request->slot_id;

            // Delete the timeslot
            DB::table('timeslots')->where('id', $id)->delete();

            return response()->json(['code' => 200, 'message' => 'Timeslot deleted successfully']);
        } catch (\Exception $e) {
            // In case of an exception, return an error message
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAllSlots(Request $request)
    {
        // Validate doctor_id is provided
        $validate = Validator::make($request->all(), [
            'doctor_id' => 'required',      // Ensure doctor exists
            'date' => 'nullable|date', // Validate date if provided
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            // Query timeslots for the specific doctor
            $query = DB::table('timeslots')
                ->where('user_id', $request->doctor_id);

            // Apply date filter if provided
            if ($request->has('date')) {
                $query->whereDate('date', $request->date);
            }

            $timeslots = $query->get();

            if ($timeslots->isEmpty()) {
                return response()->json(['code' => 404, 'message' => 'No timeslots found for this doctor'], 404);
            }

            return response()->json(['code' => 200, 'message' => 'Timeslots retrieved successfully', 'data' => $timeslots]);
        } catch (\Exception $e) {
            // In case of an exception, return an error message
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function getCategories(Request $request)
    {
        // Step 1: Get only category IDs which have levels
        $validCategoryIds = DB::table('category_levels')
            ->select('category_id')
            ->distinct()
            ->pluck('category_id')
            ->toArray();

        // Step 2: Apply filters on categories that have levels
        $query = DB::table('category')->whereIn('id', $validCategoryIds);

        if ($request->filled('search')) {
            $query->where('category_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('category_for', ucfirst($request->role));
        }

        // Step 3: Paginate
        $perPage = $request->input('per_page', 40);
        $categories = $query->paginate($perPage);

        if ($categories->isEmpty()) {
            return response()->json([
                'code' => 404,
                'message' => 'No categories found',
            ]);
        }

        // Step 4: Fetch category levels in bulk
        $categoryIds = $categories->pluck('id')->toArray();
        $allLevels = DB::table('category_levels')
            ->whereIn('category_id', $categoryIds)
            ->get()
            ->groupBy('category_id');

        // Step 5: Format response
        $data = $categories->map(function ($category) use ($allLevels) {
            $levelsData = $allLevels->get($category->id, collect());

            // Format levels
            $levels = $levelsData
                ->groupBy('level')
                ->map(function ($group, $level) {
                    return [
                        'level' => (int) $level,
                        'options' => $group->map(function ($item) {
                            return [
                                'session_type' => $item->session_type,
                                'sessions' => $item->sessions,
                                'price' => $item->price,
                            ];
                        })->values(),
                    ];
                })->values();

            // Category response
            return [
                'id' => $category->id,
                'category_name' => $category->category_name,
                'category_for' => $category->category_for,
                'description' => $category->description ?? '',
                'icon' => isset($category->icon) ? url('uploads/category/' . $category->icon) : '',
                'cover_image' => isset($category->coverimage) ? url('uploads/category/' . $category->coverimage) : '',
                'levels' => $levels,
            ];
        });

        return response()->json([
            'code' => 200,
            'message' => 'Categories with levels retrieved successfully',
            'data' => $data,
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'last_page' => $categories->lastPage(),
            ],
        ]);
    }



    public function submitReview(Request $request)
    {
        // Validate doctor_id, user_id, rating, and review are provided
        $validate = Validator::make($request->all(), [
            'doctor_id' => 'required', // Ensure doctor exists
            'user_id' => 'required',
            'rating' => 'required',
            'review' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            $save = [
                'doctor_id' => $request->doctor_id,
                'user_id' => $request->user_id,
                'rating' => $request->rating,
                'review' => $request->review,
                'created_at' => now(),
            ];

            // Insert the review data into the database
            $insertStatus = DB::table('reviews')->insert($save);

            if ($insertStatus) {
                return response()->json(['code' => 200, 'message' => 'Review Submitted successfully!'], 200);
            } else {
                return response()->json(['code' => 500, 'message' => 'Something Went Wrong!'], 500);
            }
        } catch (\Exception $e) {
            // In case of an exception, return an error message
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    // public function getReviewsByDoctor(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'doctor_id' => 'required|exists:doctors,id', // Ensure doctor exists

    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
    //     }
    //     try {
    //         // Retrieve all reviews for the specified doctor_id
    //         $doctor_id = $request->doctor_id;
    //         $reviews   = DB::table('reviews')->where('doctor_id', $doctor_id)->orderby('id', 'desc')->get();

    //         if ($reviews->isEmpty()) {
    //             return response()->json(['code' => 404, 'message' => 'No reviews found for this doctor'], 404);
    //         }

    //         // Return reviews with success response
    //         return response()->json(['code' => 200, 'message' => 'Reviews retrieved successfully', 'data' => $reviews], 200);

    //     } catch (\Exception $e) {
    //         // In case of an exception, return an error message
    //         return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
    //     }
    // }

    public function getReviews(Request $request)
    {
        $request->validate([
            'doctor_id' => 'nullable|exists:user_table,id',
            'user_id' => 'nullable|exists:user_table,id'
        ]);

        $reviews = DB::table('reviews')
            ->when($request->doctor_id, function ($q) use ($request) {
                $q->where('doctor_id', $request->doctor_id);
            })
            ->when($request->user_id, function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($review) {
                $user = DB::table('user_table')
                    ->where('id', $review->user_id)
                    ->select('id', 'name', 'email', 'image')
                    ->first();

                $doctor = DB::table('user_table')
                    ->where('id', $review->doctor_id)
                    ->select('id', 'name', 'email', 'image')
                    ->first();

                return [
                    'rating' => $review->rating,
                    'comment' => $review->review,
                    'created_at' => $review->created_at,
                    'user' => $user,
                    'doctor' => $doctor
                ];
            });

        if ($reviews->isEmpty()) {
            return response()->json(['code' => 404, 'message' => 'No reviews found'], 404);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Reviews retrieved successfully',
            'data' => $reviews
        ]);
    }

    public function BookAppoinment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'slot_id' => 'required',
            'user_id' => 'required',
            'address_id' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        } else {
            try {
                // Retrieve all reviews for the specified doctor_id
                $user_id = $request->user_id;
                $slot_id = $request->slot_id;

                // check if the slot already booked or not
                $checkAlreadyBooked = DB::table('timeslots')->where('id', $slot_id)->where('isBooked', true)->first();

                if (!empty($checkAlreadyBooked)) {
                    return response()->json(['code' => 401, 'message' => 'This slot already booked by someone!', 'alreadyBooked' => true], 401);
                }
                $reviews = DB::table('timeslots')->where('id', $slot_id)->update(['isBooked' => true, 'bookedBy' => $user_id, 'booked_date' => now(), 'address_id' => $request->address_id]);
                $reviews = true;

                if ($reviews != true) {
                    return response()->json(['code' => 500, 'message' => 'Something Went wrong!'], 500);
                } else {
                    $timesl = DB::table('timeslots')->where('id', $slot_id)->first();

                    $userinfo = DB::table('user_table')->where('id', $timesl->user_id)->first();
                    $userinfo2 = DB::table('user_table')->where('id', $timesl->bookedBy)->first();

                    $doctorName = $userinfo->name; // Example doctor name
                    $patientName = $userinfo2->name;
                    $appointmentDate = date('d-m-y', strtotime($timesl->booked_date)); // Example date
                    $appointmentTime = $timesl->start_time;                            // Example time

                    $notificationMessage = "Hello, your appointment with $doctorName has been successfully booked for $appointmentDate at $appointmentTime. We look forward to seeing you!";

                    DB::table('notifications')->insert(['user_id' => $user_id, 'message' => $notificationMessage, 'corresponding_user' => $timesl->user_id, 'created_at' => now()]);

                    $notificationMessageForDoctor = "Hello Dr. $doctorName, you have a new appointment with $patientName on $appointmentDate at $appointmentTime. Please be prepared to see your patient. Thank you!";

                    DB::table('notifications')->insert(['user_id' => $userinfo->id, 'message' => $notificationMessageForDoctor, 'corresponding_user' => $timesl->bookedBy, 'created_at' => now()]);

                    // Return reviews with success response
                    return response()->json(['code' => 200, 'message' => 'Appointment Booked Successfully!'], 200);
                }
            } catch (\Exception $e) {
                // In case of an exception, return an error message
                return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
            }
        }
    }

    public function addAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'full_address' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        } else {
            try {
                $save['user_id'] = $request->user_id;
                $save['type'] = $request->type;
                $save['city'] = $request->city;
                $save['state'] = $request->state;
                $save['zip_code'] = $request->zip_code;
                $save['full_address'] = $request->full_address;
                $save['created_at'] = now();
                $insert = DB::table('user_address')->insert($save);
                if ($insert) {
                    return response()->json(['code' => 200, 'message' => 'User Address added  Successfully!'], 200);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Something Went wrong!'], 500);
                }
            } catch (\Exception $e) {
                // In case of an exception, return an error message
                return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
            }
        }
    }

    public function deleteAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'address_id' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        } else {
            try {
                $delete = DB::table('user_address')->where('id', $request->address_id)->delete();
                if ($delete) {
                    return response()->json(['code' => 200, 'message' => 'User Address deleted  Successfully!'], 200);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Something Went wrong!'], 500);
                }
            } catch (\Exception $e) {
                // In case of an exception, return an error message
                return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
            }
        }
    }

    public function updateAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'full_address' => 'required',
            'address_id' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        } else {
            try {
                $save['user_id'] = $request->user_id;
                $save['type'] = $request->type;
                $save['city'] = $request->city;
                $save['state'] = $request->state;
                $save['zip_code'] = $request->zip_code;
                $save['full_address'] = $request->full_address;

                $save['updated_at'] = now();
                $update = DB::table('user_address')->where('id', $request->address_id)->update($save);
                if ($update) {
                    return response()->json(['code' => 200, 'message' => 'User Address Updated   Successfully!'], 200);
                } else {
                    return response()->json(['code' => 500, 'message' => 'Something Went wrong!'], 500);
                }
            } catch (\Exception $e) {
                // In case of an exception, return an error message
                return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
            }
        }
    }

    public function fetchAddress(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',

        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        } else {
            try {
                $get = DB::table('user_address')->where('user_id', $request->user_id)->get();
                if ($get) {
                    return response()->json(['code' => 200, 'message' => 'User Address found  Successfully!', 'data' => $get], 200);
                } else {
                    return response()->json(['code' => 200, 'message' => 'Something Went wrong!'], 500);
                }
            } catch (\Exception $e) {
                // In case of an exception, return an error message
                return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
            }
        }
    }

    public function getDoctorsList(Request $request)
    {
        $genre = $request->input('role', 'doctor');
        if ($genre == 'doctor' || $genre == 'nurse') {
            $this->updateImageLoop();
        }

        // Initialize the query for doctors
        $query = DB::table('user_table')
            ->leftJoin('doctor', 'doctor.doctor_id', '=', 'user_table.id')
            ->leftJoinSub(
                DB::table('reviews')
                    ->select('doctor_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(id) as total_reviews'))
                    ->groupBy('doctor_id'),
                'reviews',
                'reviews.doctor_id',
                '=',
                'user_table.id'
            )
            ->where('user_table.genre', $genre)
            ->orderBy('user_table.id', 'desc')
            ->select(
                'user_table.id',
                'user_table.name',
                'user_table.phone',
                'user_table.email',
                'user_table.image',
                'doctor.unique_id',
                'doctor.fees',
                'doctor.degree',
                'doctor.specialization',
                'doctor.experience_year',
                DB::raw('COALESCE(reviews.avg_rating, 0) as avg_rating'),
                DB::raw('COALESCE(reviews.total_reviews, 0) as total_reviews')
            );

        // Apply search filter, including category name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_table.name', 'LIKE', "%$search%")
                    ->orWhere('user_table.email', 'LIKE', "%$search%")
                    ->orWhere('doctor.degree', 'LIKE', "%$search%")
                    ->orWhereExists(function ($subquery) use ($search) {
                        $subquery->select(DB::raw(1))
                            ->from('doctor_category')
                            ->join('category', 'category.id', '=', 'doctor_category.category_id')
                            ->whereRaw('doctor_category.doctor_id = doctor.doctor_id')
                            ->where('category.category_name', 'LIKE', "%$search%");
                    });
            });
        }

        if ($genre == 'doctor' || $genre == 'nurse') {
            $query->where('user_table.flag', 0);

            if ($request->has('gender')) {
                $query->where('doctor.gender', $request->gender);
            }
            if ($request->has('experience')) {
                if ($request->experience > 0) {
                    $query->where('doctor.experience_year', $request->experience);
                }
            }

            if ($request->filled('location')) {
                $locations = $request->location;
                $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(doctor.location, '$[*]')) LIKE ?", ["%$locations%"]);
            }

            if ($request->has('fees')) {

                $query->where('doctor.fees', '<=', $request->fees);
            }
        }
        // Fetch doctors
        $perPage = $request->input('per_page', 10);
        $doctors = $query->paginate($perPage);

        if ($doctors->isEmpty()) {
            return response()->json(['code' => 404, 'message' => 'No Doctors Found'], 404);
        }

        // Fetch category names for each doctor
        $doctorIds = $doctors->pluck('id');
        $categories = DB::table('doctor_category')
            ->join('category', 'category.id', '=', 'doctor_category.category_id')
            ->whereIn('doctor_category.doctor_id', $doctorIds)
            ->select('doctor_category.doctor_id', 'category.category_name')
            ->get()
            ->groupBy('doctor_id');

        $data = $doctors->map(function ($doctor) use ($categories) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                'image' => $doctor->image ? asset("uploads/doctor/{$doctor->image}") : '',
                'unique_id' => $doctor->unique_id,
                'fees' => $doctor->fees,
                'degree' => json_decode($doctor->degree),
                'experience_year' => $doctor->experience_year,
                'avg_rating' => round($doctor->avg_rating, 1),
                'total_reviews' => $doctor->total_reviews,
                'categories' => $categories->get($doctor->id, collect())->pluck('category_name')->toArray(),
            ];
        });

        return response()->json([
            'code' => 200,
            'message' => 'Doctors found successfully',
            'data' => $data,
            'pagination' => [
                'current_page' => $doctors->currentPage(),
                'per_page' => $doctors->perPage(),
                'total' => $doctors->total(),
                'last_page' => $doctors->lastPage(),
            ],
        ]);
    }

    public function getDoctorById(Request $request)
    {


        $doctor = DB::table('user_table')
            ->leftJoin('doctor', 'doctor.doctor_id', '=', 'user_table.id')
            ->leftJoin('category', 'category.id', '=', 'doctor.category_id')
            ->leftJoin('reviews', 'reviews.doctor_id', '=', 'user_table.id')
            ->where('user_table.id', $request->id)
            ->select(
                'user_table.id',
                'user_table.name',
                'user_table.phone',
                'user_table.email',
                'user_table.image',
                'user_table.level',
                'doctor.unique_id',
                'doctor.fees',
                'doctor.degree',
                'doctor.gender',
                'doctor.description',
                'doctor.experience_year',
                'doctor.current_workplace',
                'doctor.previous_orgnisation',
                'doctor.area_of_expertise',
                'doctor.category_id',
                'doctor.address_line_1',
                'doctor.address_line_2',
                'doctor.emergency',
                'doctor.relation',
                'doctor.bank_name',
                'doctor.holder_name',
                'doctor.account_number',
                'doctor.ifsc_code',
                'doctor.upi_id',
                'doctor.employment_type',
                'doctor.adhar_proof',
                'doctor.pan_proof',
                'doctor.image',
                'doctor.degree_proof',
                'doctor.registration_proof',
                'doctor.cheque',
                'doctor.video_proof',
                'doctor.signature',
                'category.category_name',
                DB::raw('COALESCE(AVG(reviews.rating), 0) as avg_rating'),
                DB::raw('COUNT(reviews.id) as total_reviews')
            )
            ->groupBy(
                'user_table.id',
                'user_table.name',
                'user_table.phone',
                'user_table.email',
                'user_table.image',
                'user_table.level',
                'doctor.unique_id',
                'doctor.description',
                'doctor.fees',
                'doctor.degree',
                'doctor.gender',
                'doctor.experience_year',
                'doctor.current_workplace',
                'doctor.previous_orgnisation',
                'doctor.area_of_expertise',
                'doctor.category_id',
                'doctor.address_line_1',
                'doctor.address_line_2',
                'doctor.emergency',
                'doctor.relation',
                'doctor.bank_name',
                'doctor.holder_name',
                'doctor.account_number',
                'doctor.ifsc_code',
                'doctor.upi_id',
                'doctor.employment_type',
                'doctor.adhar_proof',
                'doctor.pan_proof',
                'doctor.degree_proof',
                'doctor.image',
                'doctor.registration_proof',
                'doctor.cheque',
                'doctor.video_proof',
                'doctor.signature',
                'category.category_name'
            )
            ->first();

        if (!$doctor) {
            return response()->json(['code' => 404, 'message' => 'Doctor not found'], 404);
        }

        // Format image and file URLs
        $formatUrl = fn($file) => $file ? $file : null;

        // Fetch attendance count
        $attended = DB::table('timeslots')
            ->where('user_id', $request->id)
            ->where('iscompleted', 1)
            ->count();

        // Fetch category level info
        $levelNumber = (int) filter_var($doctor->level, FILTER_SANITIZE_NUMBER_INT);
        $levelInfo = DB::table('category_levels')
            ->where('category_id', $doctor->category_id)
            ->where('level', $levelNumber)
            ->get(['session_type', 'sessions', 'price']);

        return response()->json([
            'code' => 200,
            'message' => 'Doctor profile fetched successfully',
            'data' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'level' => $doctor->level,
                'image' => $formatUrl($doctor->image),
                'unique_id' => $doctor->unique_id,
                'fees' => $doctor->fees,
                'degree' => json_decode($doctor->degree ?? '[]'),
                'experience_year' => $doctor->experience_year,
                'gender' => $doctor->gender,
                'area_of_expertise' => $doctor->area_of_expertise,
                'current_workplace' => $doctor->current_workplace,
                'previous_orgnisation' => $doctor->previous_orgnisation,
                'category_id' => $doctor->category_id,
                'category_name' => $doctor->category_name,
                'address_line_1' => $doctor->address_line_1,
                'address_line_2' => $doctor->address_line_2,
                'description' => $doctor->description,
                'emergency' => $doctor->emergency,
                'relation' => $doctor->relation,
                'bank_name' => $doctor->bank_name,
                'holder_name' => $doctor->holder_name,
                'account_number' => $doctor->account_number,
                'ifsc_code' => $doctor->ifsc_code,
                'upi_id' => $doctor->upi_id,
                'employment_type' => $doctor->employment_type,
                'adhar_proof' => $formatUrl($doctor->adhar_proof),
                'pan_proof' => $formatUrl($doctor->pan_proof),
                'degree_proof' => $formatUrl($doctor->degree_proof),
                'registration_proof' => $formatUrl($doctor->registration_proof),
                'cheque' => $formatUrl($doctor->cheque),
                'video_proof' => $formatUrl($doctor->video_proof),
                'signature' => $formatUrl($doctor->signature),
                'avg_rating' => round($doctor->avg_rating, 1),
                'total_reviews' => $doctor->total_reviews,
                'attended' => $attended,
                'level_info' => $levelInfo
            ]
        ]);
    }


    public function getAllBookings(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();
            $where = ($user->genre == 'user') ? 'bookedBy' : 'user_id';
            $otherInfo = ($where == 'user_id') ? 'bookedBy' : 'user_id';

            // Initialize query
            $query = DB::table('timeslots')
                ->where('timeslots.isbooked', 1)
                ->where('timeslots.' . $where, $user->id)
                ->leftJoin('user_address', 'user_address.id', '=', 'timeslots.address_id')
                ->leftJoin('user_table', 'user_table.id', '=', 'timeslots.' . $otherInfo)
                ->leftJoin('doctor', 'doctor.doctor_id', '=', 'timeslots.user_id')
                ->leftJoin('doctor_category', 'doctor_category.doctor_id', '=', 'doctor.doctor_id')
                ->leftJoin('category', 'category.id', '=', 'doctor_category.category_id');

            // Apply status filter if provided
            if ($request->has('status')) {
                if ($request->status == 'completed') {
                    $query->where('iscompleted', 1);
                }
                if ($request->status == 'upcoming') {
                    $query->where('iscompleted', 0);
                }
            }

            // Apply date filters
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('timeslots.date', [$request->start_date, $request->end_date]);
            } elseif ($request->has('start_date')) {
                $query->where('timeslots.date', '>=', $request->start_date);
            } elseif ($request->has('end_date')) {
                $query->where('timeslots.date', '<=', $request->end_date);
            }

            if ($request->has('search') && !empty($request->search)) {
                $query->where(function ($q) use ($request) {
                    $q->where('user_table.name', 'like', '%' . $request->search . '%')
                        ->orWhere('user_table.phone', 'like', '%' . $request->search . '%')
                        ->orWhere('user_table.email', 'like', '%' . $request->search . '%');
                });
            }

            // Only add future date condition if no filters are applied
            if (!$request->has('status') && !$request->has('start_date') && !$request->has('end_date')) {
                $query->where('timeslots.date', '>=', date('Y-m-d'));
            }

            // Select required columns & group correctly
            $bookings = $query->select(
                'timeslots.id',
                'timeslots.date',
                'timeslots.start_time',
                'timeslots.end_time',
                'timeslots.payment_status',
                'timeslots.issue_with_patient',
                // Removed 'timeslots.time' since it does not exist
                'timeslots.isbooked',
                'timeslots.prescription',
                'timeslots.iscompleted',
                'user_address.type',
                'user_address.full_address',
                'user_table.name as doctor_name',
                'user_table.phone as doctor_phone',
                'user_table.id as doctor_id',
                'user_table.image as doctor_image2',
                DB::raw('COALESCE(GROUP_CONCAT(DISTINCT category.category_name SEPARATOR ", "), "") as categories')
            )
                ->groupBy(
                    'timeslots.id',
                    'timeslots.start_time',
                    'timeslots.end_time',
                    'timeslots.payment_status',
                    'timeslots.issue_with_patient',

                    'timeslots.date',
                    'timeslots.isbooked',
                    'timeslots.prescription',
                    'timeslots.iscompleted',
                    'user_address.type',
                    'user_address.full_address',
                    'user_table.name',
                    'user_table.phone',
                    'user_table.id',
                    'user_table.image'
                )
                ->get();

            // Format doctor image URLs
            if ($bookings->isNotEmpty()) {
                $bookings = $bookings->map(function ($booking) {
                    $booking->doctor_image = $booking->doctor_image2 ? url('uploads/doctor/' . $booking->doctor_image2) : '';
                    return $booking;
                });

                return response()->json([
                    'code' => 200,
                    'message' => 'Bookings found successfully',
                    'data' => $bookings,
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'No Bookings Found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function markCompleted(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'slot_id' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            $updateSlot = DB::table('timeslots')->where('id', $request->slot_id)->update(['iscompleted' => 1, 'completed_at' => now()]);

            if ($updateSlot) {
                return response()->json(['code' => 200, 'message' => 'Appointment Completed  Successfully!'], 200);
            } else {
                return response()->json(['code' => 500, 'message' => ["error" => 'Something went wrong']], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }
    public function markAddressSelected(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            $updateSlot = DB::table('user_address')->where('id', $request->address_id)->update(['is_selected' => 1, 'updated_at' => now()]);

            if ($updateSlot) {
                // now udpdate all the other address disselected
                $userid = DB::table('user_address')->where('id', $request->address_id)->first();

                DB::table('user_address')->where('user_id', $userid->user_id)->where('id', '!=', $request->address_id)->update(['is_selected' => 0, 'updated_at' => now()]);
                return response()->json(['code' => 200, 'message' => 'Address Selected  Successfully!'], 200);
            } else {
                return response()->json(['code' => 404, 'message' => 'No selected Address  Found!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }
    public function getselectedAddress(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();

            // Fetch the selected address
            $getcurrrectSelectedAddeess = DB::table('user_address')
                ->where('user_id', $user->id)
                ->where('is_selected', 1)
                ->orderBy('id', 'desc')
                ->first();

            if ($getcurrrectSelectedAddeess) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Address found successfully',
                    'data' => $getcurrrectSelectedAddeess,
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'No address found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
    public function getAllChatMessages(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;

            // Fetch messages with sender and receiver data and read count
            $messages = DB::table('messages as m')
                ->join('user_table as sender', 'sender.id', '=', 'm.sender_id')
                ->join('user_table as receiver', 'receiver.id', '=', 'm.receiver_id')
                ->whereRaw('m.id = (
        SELECT MAX(id)
        FROM messages
        WHERE
            ((sender_id = ? OR receiver_id = ?) AND
            ((sender_id = m.sender_id AND receiver_id = m.receiver_id)
            OR (sender_id = m.receiver_id AND receiver_id = m.sender_id)))
    )', [$userId, $userId])
                ->select(
                    'm.*',
                    'sender.name as sender_name',
                    'sender.email as sender_email',
                    'sender.phone as sender_phone',
                    'receiver.name as receiver_name',
                    'receiver.email as receiver_email',
                    'receiver.phone as receiver_phone',
                    DB::raw("CONCAT('" . url('uploads/doctor') . "/', sender.image) as sender_image"),
                    DB::raw("CONCAT('" . url('uploads/doctor') . "/', receiver.image) as receiver_image")
                )
                ->get();

            if ($messages->isNotEmpty()) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Chat messages found successfully',
                    'data' => $messages,
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'No chat found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function getInnerChat(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();

            // Get per_page and page from request (default per_page=10, page=1)
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            // Fetch paginated messages with sender and receiver data
            $messages = DB::table('messages as m')
                ->join('user_table as sender', 'sender.id', '=', 'm.sender_id')
                ->join('user_table as receiver', 'receiver.id', '=', 'm.receiver_id')
                ->where(function ($query) use ($request) {
                    $query->where('m.sender_id', $request->sender_id)
                        ->where('m.receiver_id', $request->receiver_id);
                })
                ->orWhere(function ($query) use ($request) {
                    $query->where('m.sender_id', $request->receiver_id)
                        ->where('m.receiver_id', $request->sender_id);
                })
                ->select(
                    'm.*',
                    'sender.name as sender_name',
                    'sender.email as sender_email',
                    'sender.phone as sender_phone',
                    'receiver.name as receiver_name',
                    'receiver.email as receiver_email',
                    'receiver.phone as receiver_phone',
                    DB::raw("CONCAT('" . url('uploads/doctor') . "/', sender.image) as sender_image"),
                    DB::raw("CONCAT('" . url('uploads/doctor') . "/', receiver.image) as receiver_image")
                )
                ->paginate($perPage, ['*'], 'page', $page); // Apply pagination with custom page

            return response()->json([
                'code' => 200,
                'message' => 'Chat messages retrieved successfully',
                'data' => $messages->items(),
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                    'last_page' => $messages->lastPage(),
                    'results' => count($messages->items()),
                    'next_page_url' => $messages->nextPageUrl(),
                    'prev_page_url' => $messages->previousPageUrl(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getallcat(Request $request)
    {
        // Category Query
        $query = DB::table('category');
        if ($request->filled('search')) {
            $query->where('category_name', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->filled('role')) {
            $query->where('category_for', ucfirst($request->role));
        }
        $categories = $query->get();

        // Location Query (corrected table name)
        $locationsQuery = DB::table('locationsssssss');
        if ($request->filled('location_name')) {
            $locationsQuery->where('location_name', 'LIKE', '%' . $request->location_name . '%');
        }
        $locations = $locationsQuery->get();

        // Degree Query
        $degreeQuery = DB::table('degrees');
        if ($request->filled('degree_name')) {
            $degreeQuery->where('location_name', 'LIKE', '%' . $request->degree_name . '%');
        }
        $degree = $degreeQuery->where('flag', 0)->select('id', 'location_name as degree')->get();

        // Certificate Query
        $certificateQuery = DB::table('certificates');
        if ($request->filled('certificate_name')) {
            $certificateQuery->where('location_name', 'LIKE', '%' . $request->certificate_name . '%');
        }
        $certificates = $certificateQuery->where('flag', 0)->select('id', 'location_name as certificate')->get();

        // Format categories and attach category_levels
        $data = $categories->map(function ($listing) {
            // Get related category_levels
            $categoryLevels = DB::table('category_levels')
                ->where('category_id', $listing->id)
                ->get();

            // Return category_levels as a flat array
            $levels = $categoryLevels->map(function ($level) {
                return [
                    'level' => $level->level,
                    'session_type' => $level->session_type,
                    'sessions' => $level->sessions,
                    'price' => $level->price,
                ];
            });

            return [
                'id' => $listing->id,
                'category_name' => $listing->category_name,
                'icon' => url('uploads/category/' . $listing->icon),
                'category_for' => $listing->category_for,
                'levels' => $levels, // Flattened levels data
            ];
        });

        return response()->json([
            'code' => 200,
            'message' => 'All categories found successfully',
            'data' => $data,
            'locations' => $locations,
            'degree' => $degree,
            'certificates' => $certificates,
        ]);
    }

    public function getNotifications(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();
            $notifications = DB::table('notifications')
                ->join('user_table', 'user_table.id', 'notifications.corresponding_user')
                ->where('notifications.user_id', $user->id)
                ->orderby('id', 'desc')
                ->select(
                    'notifications.*',
                    DB::raw("CONCAT('" . url('uploads/doctor') . "/', user_table.image) as corresponding_user_image"),
                )
                ->get();

            return response()->json([
                'code' => 200,
                'message' => 'Notification messages retrieved successfully',
                'data' => $notifications,

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function uploadprescription(Request $request){

    //          $validate = Validator::make($request->all(), [
    //         'timeslots_id' => 'required',
    //         'prescription'=>'required'
    //     ]);

    //     if ($validate->fails()) {
    //         return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()],401);
    //     }
    //     if ($request->has('prescription')) {
    //         $fileData = $request->input('prescription'); // Get Base64 string

    //         if (preg_match('/^data:image\/(\w+);base64,/', $fileData, $matches)) {
    //             $imageType = $matches[1]; // Extract image type (jpg, png, etc.)
    //             $fileData = substr($fileData, strpos($fileData, ',') + 1); // Remove metadata
    //             $fileData = base64_decode($fileData); // Decode base64

    //             if ($fileData === false) {
    //                 return response()->json(['code' => 400, 'message' => 'Invalid image format'], 400);
    //             }

    //             $filename = date('YmdHi') . uniqid() . '.' . $imageType;
    //             $filePath = public_path('uploads/prescription/' . $filename);

    //             file_put_contents($filePath, $fileData); // Save file

    //             $updateData['prescription'] = $fullpath = url('uploads/prescription/' . $filename);
    //             $prescription =  DB::table('timeslots')->whereId($request->timeslots_id)->update($updateData);
    //              return response()->json([
    //             'code' => 200,
    //             'message' => 'prescription Uploaded successfully',
    //             'url'=>$fullpath
    //         ], 200);

    //         } else {
    //             return response()->json(['code' => 400, 'message' => 'Invalid Base64 string'], 400);
    //         }
    // }
    // }

    public function uploadprescription(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'timeslots_id' => 'required|exists:timeslots,id',
            'prescription' => 'required', // max 5MB
        ]);

        if ($validate->fails()) {
            return response()->json([
                'code' => 401,
                'message' => $validate->errors()->toArray(),
            ], 401);
        }

        if ($request->hasFile('prescription')) {
            $file = $request->file('prescription');
            $filename = date('YmdHi') . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/prescription'), $filename);

            $fileUrl = url('uploads/prescription/' . $filename);

            DB::table('timeslots')
                ->whereId($request->timeslots_id)
                ->update(['prescription' => $fileUrl]);

            return response()->json([
                'code' => 200,
                'message' => 'Prescription uploaded successfully',
                'url' => $fileUrl,
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'No file uploaded',
            ], 400);
        }
    }

    public function uploadUserImage(Request $request)
    {

        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();

            $validate = Validator::make($request->all(), [
                'image' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
            }
            if ($request->has('image')) {
                $fileData = $request->input('image'); // Get Base64 string

                if (preg_match('/^data:image\/(\w+);base64,/', $fileData, $matches)) {
                    $imageType = $matches[1];                                   // Extract image type (jpg, png, etc.)
                    $fileData = substr($fileData, strpos($fileData, ',') + 1); // Remove metadata
                    $fileData = base64_decode($fileData);                      // Decode base64

                    if ($fileData === false) {
                        return response()->json(['code' => 400, 'message' => 'Invalid image format'], 400);
                    }

                    $filename = date('YmdHi') . uniqid() . '.' . $imageType;
                    $filePath = public_path('uploads/doctor/' . $filename);

                    file_put_contents($filePath, $fileData); // Save file

                    $updateData['image'] = $fullpath = $filename;

                    $prescription = DB::table('user_table')->whereId($user->id)->update($updateData);
                    return response()->json([
                        'code' => 200,
                        'message' => 'User Profile Image Uploaded successfully',
                        'url' => url('uploads/doctor/' . $filename),
                    ], 200);
                } else {
                    return response()->json(['code' => 400, 'message' => 'Invalid Base64 string'], 400);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function reseduleOption(Request $request)
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();

            // Validate the input data
            $validate = Validator::make($request->all(), [
                'current_timeslots_id' => 'required',
                'future_timeslot_id' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
            }

            // Check if the future timeslot exists using the Query Builder
            $checkExistence = DB::table('timeslots')->where('id', $request->future_timeslot_id)->first();

            if ($checkExistence) {
                // Check if the future timeslot is already booked
                if ($checkExistence->isBooked == 1) {
                    return response()->json(['code' => 422, 'message' => 'Timeslot already booked by someone else'], 400);
                }

                // Get the current timeslot info using the Query Builder
                $currentTimeSlotInfo = DB::table('timeslots')->where('id', $request->current_timeslots_id)->first();

                if (!$currentTimeSlotInfo) {
                    return response()->json(['code' => 400, 'message' => 'Current timeslot does not exist'], 400);
                }

                // Update the future timeslot using Query Builder
                DB::table('timeslots')->where('id', $request->future_timeslot_id)->update([
                    'isBooked' => 1,
                    'bookedBy' => $user->id,
                    'address_id' => $currentTimeSlotInfo->address_id,
                    'is_resheduled' => 1,
                    'booked_date' => now(),
                ]);

                // Update the current timeslot as unbooked using Query Builder
                DB::table('timeslots')->where('id', $request->current_timeslots_id)->update([
                    'isBooked' => 0,
                    'bookedBy' => null, // Use null instead of a blank space for consistency
                    'address_id' => null, // Use null instead of a blank space for consistency
                    'is_resheduled' => 2,
                    'booked_date' => null, // Use null instead of a blank space for consistency
                ]);

                return response()->json(['code' => 200, 'message' => 'Appointment resheduled successfully!'], 200);
            } else {
                return response()->json(['code' => 400, 'message' => 'Timeslot does not exist'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function otploginForForgetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required',
        ], ['phone.required' => 'This field is required!']);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            $otp = 123456;

            // Check if the phone or email exists in otpVerify table
            $checkExist = otpVerify::where('phone', $request->phone)
                ->orWhere('email', $request->phone)
                ->first();

            if (!empty($checkExist)) {
                // Update OTP if exists in otpVerify
                $checkExist->update(['otp' => $otp, 'created_at' => now()]);
                return response()->json(['code' => 200, 'message' => 'OTP sent Successfully'], 200);
            }

            // Check if the phone or email exists in the user_table
            $userExist = DB::table('user_table')
                ->where('phone', $request->phone)
                ->orWhere('email', $request->phone)
                ->first();

            if (!empty($userExist)) {

                $checkExist = otpVerify::where('phone', $userExist->phone)
                    ->orWhere('email', $userExist->phone)
                    ->first();

                if (!empty($checkExist)) {
                    // Update OTP if exists in otpVerify
                    $checkExist->update(['otp' => $otp, 'created_at' => now()]);
                    return response()->json(['code' => 200, 'message' => 'OTP sent Successfully'], 200);
                }
            }

            return response()->json(['code' => 401, 'message' => 'User is not registered!'], 401);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateImageLoop()
    {
        $records = DB::table('user_table')
            ->leftJoin('doctor', 'user_table.id', '=', 'doctor.doctor_id')
            ->select('user_table.id as user_id', 'user_table.image', 'doctor.doctor_id', 'doctor.image as doctor_image')
            ->get();

        foreach ($records as $record) {
            if (!empty($record->user_image) && empty($record->doctor_image)) {
                // Update doctor table if user has an image but doctor does not
                DB::table('doctor')
                    ->where('doctor_id', $record->user_id)
                    ->update(['image' => $record->user_image]);
            } elseif (!empty($record->doctor_image) && empty($record->user_image)) {
                // Update user_table if doctor has an image but user does not
                DB::table('user_table')
                    ->where('id', $record->user_id)
                    ->update(['image' => $record->doctor_image]);
            }
        }
    }

    public function updateNotifications()
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();

            // Update notifications for the authenticated user
            DB::table('notifications')
                ->where('user_id', $user->id)
                ->update(['is_read' => 1, 'updated_at' => now()]);

            return response()->json(['code' => 200, 'message' => 'Notifications updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function getNotificationCount()
    {
        try {
            // Get authenticated user from token
            $user = JWTAuth::parseToken()->authenticate();

            // GET THE total of the fees
            $count = DB::table('notifications')
                ->where('user_id', $user->id)->where('is_read', 0)->count();

            return response()->json([
                'code' => 200,
                'message' => 'Unread notifications count retrieved successfully!',
                'count' => $count,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function getwallet(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Fetch doctor's fees
            $fees = DB::table('doctor')
                ->where('doctor_id', $user->id)
                ->select('id', 'fees')
                ->first();

            if (empty($fees->fees)) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Please contact with admin to update the fees!',
                ], 401);
            }

            // Fetch optional month filter from request (format: YYYY-MM)
            $monthFilter = $request->query('month'); // Example: '2024-04'

            // Total income
            $totalAppointments = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->count();

            $RAppointments = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->where('paid_by_admin', 1)
                ->count();

            $totalIncome = $totalAppointments * $fees->fees;
            $remiaing = $totalIncome - $RAppointments * $fees->fees;
            $t = $RAppointments * $fees->fees;

            // This month's income
            $currentMonthAppointments = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->where('paid_by_admin', 1)
                ->whereMonth('completed_at', date('m'))
                ->whereYear('completed_at', date('Y'))
                ->count();

            // get cuurent montht income

            $currentMonthIncome = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->whereMonth('completed_at', date('m'))
                ->whereYear('completed_at', date('Y'))
                ->where('paid_by_admin', 0)
                ->count();

            $currentMonthIncome = $currentMonthAppointments * $fees->fees;

            // If month filter is passed
            $filteredIncome = null;
            if ($monthFilter) {
                [$year, $month] = explode('-', $monthFilter);

                $filteredAppointments = DB::table('timeslots')
                    ->where('user_id', $user->id)
                    ->where('iscompleted', 1)
                    ->where('paid_by_admin', 0)
                    ->whereMonth('completed_at', $month)
                    ->whereYear('completed_at', $year)
                    ->count();
                $filteredIncome = $filteredAppointments * $fees->fees;
            }

            return response()->json([
                'code' => 200,
                'message' => 'Wallet data retrieved successfully!',
                'total_income' => $t,
                'current_month_income' => $currentMonthIncome,
                'filtered_income' => $filteredIncome,
                'filtered_month' => $monthFilter,
                'remaining' => $remiaing,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function redeemRequest(Request $request)
    {

        try {
            $user = JWTAuth::parseToken()->authenticate();

            // Fetch doctor's fees
            $fees = DB::table('doctor')
                ->where('doctor_id', $user->id)
                ->select('id', 'fees')
                ->first();

            if (empty($fees->fees)) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Please contact with admin to update the fees!',
                ], 401);
            }

            // Fetch optional month filter from request (format: YYYY-MM)
            $monthFilter = $request->query('month'); // Example: '2024-04'

            // Total income
            $totalAppointments = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->count();

            $RAppointments = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->where('paid_by_admin', 1)
                ->count();

            $totalIncome = $totalAppointments * $fees->fees;
            $remiaing = $totalIncome - $RAppointments * $fees->fees;
            $t = $RAppointments * $fees->fees;

            // This month's income
            $currentMonthAppointments = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->where('paid_by_admin', 1)
                ->whereMonth('completed_at', date('m'))
                ->whereYear('completed_at', date('Y'))
                ->count();

            // get cuurent montht income

            $currentMonthIncome = DB::table('timeslots')
                ->where('user_id', $user->id)
                ->where('iscompleted', 1)
                ->whereMonth('completed_at', date('m'))
                ->whereYear('completed_at', date('Y'))
                ->where('paid_by_admin', 0)
                ->count();

            $currentMonthIncome = $currentMonthAppointments * $fees->fees;

            // If month filter is passed
            $filteredIncome = null;
            if ($monthFilter) {
                [$year, $month] = explode('-', $monthFilter);

                $filteredAppointments = DB::table('timeslots')
                    ->where('user_id', $user->id)
                    ->where('iscompleted', 1)
                    ->where('paid_by_admin', 0)
                    ->whereMonth('completed_at', $month)
                    ->whereYear('completed_at', $year)
                    ->count();
                $filteredIncome = $filteredAppointments * $fees->fees;
            }

            //  check if the  previous request is sorted or not
            $checkRedeemRequest = DB::table('redeemrequest')->where('doctor_id', $user->id)->orderby('id', 'desc')->first();

            if (!empty($checkRedeemRequest)) {
                //   check if previous request sorted or not
                if ($checkRedeemRequest->paid == 0) {
                    return response()->json([
                        'code' => 201,
                        'message' => 'Please wait for the last approval request before creating the new one! ',

                    ], 201);
                }
            }

            // make the request redeem
            $insert = DB::table('redeemrequest')->insert(['amount' => $remiaing, 'created_at' => now(), 'doctor_id' => $user->id]);

            return response()->json([
                'code' => 200,
                'message' => 'Redeem Request Raised successfully!',

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function transactions(Request $request)
    {

        try {
            $user = JWTAuth::parseToken()->authenticate();

            $getData = DB::table('redeemrequest')->where('doctor_id', $user->id)->orderby('id', 'desc')->get();

            if (empty($getData)) {
                return response()->json([
                    'code' => 401,
                    'message' => 'No request found!',
                ], 401);
            }

            return response()->json([
                'code' => 200,
                'data' => $getData,
                'message' => 'Transactions request found successfully!',

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid or expired token',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function getallsection(Request $request)
    {
        $sections = DB::select("SELECT * FROM sections");

        $result = [];

        foreach ($sections as $section) {
            $questions = DB::select("SELECT * FROM questions WHERE section_id = ?", [$section->id]);

            foreach ($questions as &$question) {
                $answers = DB::select("SELECT id, label, text, is_correct FROM answers WHERE question_id = ?", [$question->id]);
                $question->answers = $answers;
            }

            $section->questions = $questions;
            $result[] = $section;
        }

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ], 200);
    }

    public function testoverview(Request $request)
    {
        $overview = DB::selectOne("SELECT * FROM test_overview WHERE id = 1");

        $topics = DB::select("SELECT name FROM test_topics WHERE test_overview_id = ?", [$overview->id]);

        $overview->topics = array_map(function ($t) {
            return $t->name;
        }, $topics);

        return response()->json([
            'status' => 'success',
            'data' => $overview,
        ], 200);
    }

    public function submitanswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer',
            'answers.*.answer_id' => 'required|integer',
        ], [
            'user_id.required' => 'User ID is required.',
            'answers.required' => 'Answers array is required.',
            'answers.*.question_id.required' => 'Each answer must include a question_id.',
            'answers.*.answer_id.required' => 'Each answer must include an answer_id.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->input('user_id');
        $answers = $request->input('answers');

        foreach ($answers as $answer) {
            // Check if this user already submitted an answer for this question
            $exists = DB::table('user_answers')
                ->where('user_id', $userId)
                ->where('question_id', $answer['question_id'])
                ->exists();

            if ($exists) {
                // Optional: skip, update, or return error
                continue; // skip duplicates
                /*
            DB::table('user_answers')
                ->where('user_id', $userId)
                ->where('question_id', $answer['question_id'])
                ->update([
                    'answer_id' => $answer['answer_id'],
                    'updated_at' => now(),
                ]);
            */
            } else {
                DB::table('user_answers')->insert([
                    'user_id' => $userId,
                    'question_id' => $answer['question_id'],
                    'answer_id' => $answer['answer_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Answers submitted successfully.',
        ], 200);
    }
    // this is get plans 
    public function getplans()
    {
        $plans = DB::table('plans')->where('is_active', 1)->orderBy('id', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Plans fetched successfully!',
            'data' => $plans,

        ], 200);
    }

    public function getPhysiotherapistKit()
    {
        $data = PhysiotherapistKit::orderby('id', 'desc')->get();

        return response()->json([
            'message' => 'Candidates Details found successfully!',
            'data' => $data,
            'code' => 200,
        ], 200);
    }



    // this order plans details     
    public function orderPlansDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = auth()->id();

        $plan = DB::table('plans')->where('id', $data['plan_id'])->first();

        if (!$plan) {
            return response()->json([
                'code' => 404,
                'message' => 'Plan not found',
            ], 404);
        }


        // Calculate expire date 
        $startDate = Carbon::now();

        if ($plan->duration_type === 'year') {
            $expireDate = $startDate->copy()->addYears($plan->duration);
        } elseif ($plan->duration_type === 'month') {
            $expireDate = $startDate->copy()->addMonths($plan->duration);
        } else {
            $expireDate = null;
        }

        $data['expire_date'] = $expireDate ? $expireDate->toDateString() : null;
        $plan_order_details = PlanOrderDetails::create($data);

        return response()->json([
            'message' => 'Plan Order saved successfully.',
            'data' => $plan_order_details,
            'code' => 200,
        ], 200);
    }
    // this is payments kit order 
    public function paymentKitOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:order_details,id',
            'status' => 'required|string|in:pending,paid,delivered,cancelled',
            'payment_id' => 'nullable|string',
            'transaction_data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['payment_id'] = 'PAY-' . strtoupper(uniqid()) . rand(1000, 9999);

        $payment_kit_order = PaymentKitOrder::create($data);

        // Get user info from order_details to notify
        $orderDetails = DB::table('order_details')->where('id', $request->order_id)->first();
        $user = DB::table('user_table')->where('id', $orderDetails->user_id)->first();

        if ($user) {
            $message = "Hello {$user->name}, your kit payment has been successfully recorded with status '{$data['status']}'. Thank you!";
            DB::table('notifications')->insert([
                'user_id' => $user->id,
                'message' => $message,
                'corresponding_user' => null,
                'created_at' => now()
            ]);

            // Insert into transactions
            DB::table('transactions')->insert([
                'user_id' => $user->id,
                'type' => 'kit_payment',
                'amount' => $data['transaction_data']['amount'] ?? null,
                'payment_id' => $data['payment_id'],
                'status' => $data['status'],
                'details' => json_encode($data['transaction_data']),
                'created_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Payment of kit saved successfully.',
            'data' => $payment_kit_order,
            'code' => 200,
        ], 200);
    }

    // public function paymentPlanOrder(Request $request)
    // { {
    //         $validator = Validator::make($request->all(), [
    //             'order_id'      => 'required|exists:plan_order_details,id',
    //             'status'        => 'required|string|in:pending,paid,delivered,cancelled',
    //             'payment_id'    => 'nullable|string',
    //             'transaction_data' => 'nullable|array',
    //         ]);


    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'code'    => 422,
    //                 'message' => 'Validation error',
    //                 'errors'  => $validator->errors(),
    //             ], 422);
    //         }

    //         $data = $validator->validated();
    //         $data['payment_id'] = 'PAY-' . strtoupper(uniqid()) . rand(1000, 9999);

    //         $payment_plan_orders = PaymentPlanOrder::create($data);
    //         return response()->json(data: [
    //             'message' => 'Payment of plan saved successfully.',
    //             'data'    => $payment_plan_orders,
    //             'code'    => 200,
    //         ], 200);
    //     }
    // }

    public function deleteAppointment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'slot_ids' => 'required|array|min:1',
            'user_id' => 'required|integer',
            'checkbox' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => $validate->errors()->toArray()], 401);
        }

        try {
            foreach ($request->input('slot_ids', []) as $slotId) {

                // Pehle slot fetch karo
                $slot = DB::table('timeslots')->where('id', $slotId)->first();

                if (!$slot) {
                    continue; // agar slot exist nahi karta
                }

                // Agar bookedBy same user hai
                if ($slot->bookedBy == $request->user_id) {
                    DB::table('timeslots')->where('id', $slotId)->update([
                        'isBooked' => null,
                        'bookedBy' => null,
                        'booked_date' => null,
                        'address_id' => null,
                    ]);

                    // Related notifications delete karo
                    DB::table('notifications')
                        ->where(function ($q) use ($request, $slot) {
                            $q->where('user_id', $request->user_id)
                                ->where('corresponding_user', $slot->user_id ?? null);
                        })
                        ->orWhere(function ($q) use ($request, $slot) {
                            $q->where('user_id', $slot->user_id ?? null)
                                ->where('corresponding_user', $request->user_id);
                        })
                        ->delete();
                }
            }

            return response()->json(['code' => 200, 'message' => 'Selected appointments cancelled successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }



    // purchase plan

    public function purchasePlan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required',
                'status' => 'required',
                'plan_id' => 'required|exists:plans,id',
                'transaction_data' => 'required|array',
                'user_id' => 'required|exists:users,id', // ✅ correct table name
                'transaction_data.razorpay_order_id' => 'required|string',
                'transaction_data.razorpay_payment_id' => 'required|string',
                'transaction_data.razorpay_signature' => 'required|string',
                'transaction_data.amount' => 'required|numeric',
                'transaction_data.currency' => 'required|string|size:3',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $plan = DB::table('plans')->where('id', $request->plan_id)->first();
            $startDate = Carbon::now();

            if ($plan->duration_type === 'year') {
                $data['expire_date'] = $startDate->copy()->addYears($plan->duration);
            } elseif ($plan->duration_type === 'month') {
                $data['expire_date'] = $startDate->copy()->addMonths($plan->duration);
            } else {
                $data['expire_date'] = null;
            }

            $data['order_id'] = $request->order_id;
            $data['plan_id'] = $request->plan_id;
            $data['created_at'] = now();
            $data['user_id'] = $request->user_id;

            // Check if user already has an active plan
            $activePlan = PlanOrderDetails::where('user_id', $request->user_id)
                ->where('expire_date', '>', now())
                ->first();

            if ($activePlan) {
                return response()->json([
                    'code' => 409,
                    'message' => 'You already have an active plan. Please wait for it to expire before purchasing a new one.'
                ], 409);
            }

            $purchased = PlanOrderDetails::create($data);

            PaymentPlanOrder::create([
                'order_id' => $purchased->id,
                'status' => $request->status,
                'payment_id' => $request->transaction_data['razorpay_payment_id'],
                'transaction_data' => $request->transaction_data,
            ]);

            // Notify user
            $user = DB::table('users')->where('id', $request->user_id)->first(); // ✅ correct table
            if ($user) {
                $expireText = $data['expire_date']
                    ? \Carbon\Carbon::parse($data['expire_date'])->format('d M Y')
                    : 'unlimited';

                // ✅ Full name banaya
                $fullName = trim("{$user->first_name} {$user->middle_name} {$user->last_name}");

                $message = "Hello {$fullName}, your plan '{$plan->title}' has been successfully purchased and is valid until {$expireText}.";

                DB::table('notifications')->insert([
                    'user_id' => $user->id,
                    'message' => $message,
                    'corresponding_user' => null,
                    'created_at' => now()
                ]);

                // Insert into transactions
                DB::table('transactions')->insert([
                    'user_id' => $user->id,
                    'type' => 'plan_purchase',
                    'amount' => $request->transaction_data['amount'],
                    'payment_id' => $request->transaction_data['razorpay_payment_id'],
                    'status' => $request->status,
                    'details' => json_encode($request->transaction_data),
                    'created_at' => now(),
                ]);
            }

            return response()->json(['code' => 200, 'message' => 'Plan Purchase successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function orderDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kit_id' => 'required|exists:physiotherapist_kits,id',
            'qty' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id', // fixed
            'order_id' => 'required',
            'status' => 'required',
            'transaction_data.razorpay_order_id' => 'required|string',
            'transaction_data.razorpay_payment_id' => 'required|string',
            'transaction_data.razorpay_signature' => 'required|string',
            'transaction_data.amount' => 'required|numeric',
            'transaction_data.currency' => 'required|string|size:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // save order details
        $data = [
            'kit_id' => $request->kit_id,
            'qty' => $request->qty,
            'amount' => $request->amount,
            'user_id' => $request->user_id,
            'order_id' => $request->order_id,
        ];

        $order_details = OrderDetails::create($data);

        // save payment details
        PaymentKitOrder::create([
            'order_id' => $order_details->id,
            'status' => $request->status,
            'payment_id' => $request->transaction_data['razorpay_payment_id'],
            'transaction_data' => json_encode($request->transaction_data), // ensure JSON
        ]);

        return response()->json([
            'message' => 'Kit Order saved successfully.',
            'data' => $order_details,
            'code' => 200,
        ], 200);
    }



    public function currentPlan()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $plan = PlanOrderDetails::with('plan') // eager load plan details
                ->where('user_id', $user->id)
                ->where('expire_date', '>', now())
                ->latest('created_at')
                ->first();

            if (!$plan) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No active plan found.'
                ], 404);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Active plan found.',
                'data' => [
                    'plan_id' => $plan->plan_id,
                    'plan_name' => $plan->plan->name ?? null,
                    'start_date' => Carbon::parse($plan->created_at)->toDateString(),
                    'expire_date' => Carbon::parse($plan->expire_date)->toDateString(),
                    'days_remaining' => Carbon::now()->diffInDays(Carbon::parse($plan->expire_date)),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function kitReview(StoreKitReviewRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'kit_id' => 'required|exists:physiotherapist_kits,id',
            'user_id' => 'required|exists: user_table,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Save review
        $review = KtistReview::create([
            'kit_id' => $request->ktist_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json([
            'message' => 'Review submitted successfully.',
            'data' => $review,
        ], 201);
    }




    public function basicInfo(Request $request)
    {
        try {
            $user = UserTable::where('genre', 'user')->count();
            $doctor = UserTable::where('genre', 'doctor')->count();
            $session = DB::table('timeslots')->where('iscompleted', 1)->count();

            return response()->json([
                'code' => 200,
                'message' => 'Basic info fetched successfully.',
                'data' => [
                    'user_count' => $user,
                    'doctor_count' => $doctor,
                    'completed_sessions' => $session
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }




    public function doctorProfileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:user_table,id',
            // basic info
            'name' => 'nullable',
            'contact' => 'nullable',
            'email' => 'nullable|email',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'address_line_1' => 'nullable',
            'description' => 'nullable',
            'address_line_2' => 'nullable',
            'country' => 'nullable',
            'state' => 'nullable',
            'city' => 'nullable',
            'zipcode' => 'nullable',

            // qualification
            'degree' => 'nullable',
            'college' => 'nullable',
            'category_id' => 'nullable|exists:category,id',

            // experience section
            'experience_year' => 'nullable|integer',
            'previous_orgnisation' => 'nullable|string',
            'area_of_expertise' => 'nullable',
            'current_workplace' => 'nullable',

            // bank info 
            'bank_name' => 'nullable',
            'holder_name' => 'nullable',
            'cheque' => 'nullable|image',
            'account_number' => 'nullable',
            'ifsc_code' => 'nullable',
            'upi_id' => 'nullable',

            // employment info
            'employment_type' => 'nullable',
            'willing_to_travel' => 'nullable',
            'emergency' => 'nullable|string|max:15',
            'relation' => 'nullable',
            'referral_code' => 'nullable',


            // proof
            'adhar_proof' => 'nullable|image',
            'pan_proof' => 'nullable|image',
            'degree_proof' => 'nullable|image',
            'registration_proof' => 'nullable|image',
            'signature' => 'nullable|image',
            'image' => 'nullable|image',

            // video proof
            'video_proof' => 'nullable',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
        // for the basic user info 
        $user = UserTable::where('id', $request->doctor_id)->first();
        if (!$user)
            return response()->json(['code' => 404, 'message' => 'Doctor not found!'], 404);

        // check genre 
        if ($user->genre != 'doctor')
            return response()->json(['code' => 401, 'message' => 'The given doctor id is not a doctor'], 401);


        //    # todo : update name only in user
        if ($request->filled('name')) {
            $user->name = $request->name;
            $user->save();
        }

        // check the already detail of the doctor

        // basic qualification
        $basicInfoFeilds = ['dob', 'gender', 'address_line_1', 'address_line_2', 'country', 'state', 'city', 'zipcode'];

        // qualification feilds
        $qualificationFeilds = ['degree', 'college', 'completion_year', 'category_id', 'description'];


        // EXPERIENCE FEILDS
        $experienceFeilds = ['experience_year', 'previous_orgnisation', 'area_of_expertise', 'current_workplace'];

        // bankinfo feilds

        $bankFeilds = ['bank_name', 'holder_name', 'cheque', 'account_number', 'ifsc_code', 'upi_id'];

        //employemnt info
        $emloymentFeilds = ['employment_type', 'willing_to_travel', 'emergency', 'relation', 'referral_code'];


        $doctorData = [];
        foreach ($basicInfoFeilds as $field) {
            if ($request->filled($field)) {
                $doctorData[$field] = $request->$field;
            }
        }
        foreach ($qualificationFeilds as $field) {
            if ($request->filled($field)) {
                $doctorData[$field] = $request->$field;
            }
        }
        foreach ($experienceFeilds as $field) {
            if ($request->filled($field)) {
                $doctorData[$field] = $request->$field;
            }
        }
        foreach ($bankFeilds as $field) {
            if ($request->filled($field)) {
                $doctorData[$field] = $request->$field;
            }
        }
        foreach ($emloymentFeilds as $field) {
            if ($request->filled($field)) {
                $doctorData[$field] = $request->$field;
            }
        }

        if ($request->hasFile('cheque')) {
            $signatureFile = $request->file('cheque');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/cheque'), $filename);
            $doctorData['cheque'] = url('uploads/doctor/cheque/' . $filename);
        }

        // proofs
        if ($request->hasFile('adhar_proof')) {
            $signatureFile = $request->file('adhar_proof');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/adharcard'), $filename);
            $doctorData['adhar_proof'] = url('uploads/doctor/adharcard/' . $filename);
        }
        if ($request->hasFile('pan_proof')) {
            $signatureFile = $request->file('pan_proof');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/pancard'), $filename);
            $doctorData['pan_proof'] = url('uploads/doctor/pancard/' . $filename);
        }

        if ($request->hasFile('registration_proof')) {
            $signatureFile = $request->file('registration_proof');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/registrations'), $filename);
            $doctorData['registration_proof'] = url('uploads/doctor/registrations/' . $filename);
        }

        if ($request->hasFile('degree_proof')) {
            $signatureFile = $request->file('degree_proof');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/degree'), $filename);
            $doctorData['degree_proof'] = url('uploads/doctor/degree/' . $filename);
        }
        if ($request->hasFile('signature')) {
            $signatureFile = $request->file('signature');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/signature'), $filename);
            $doctorData['signature'] = url('uploads/doctor/signature/' . $filename);
        }
        if ($request->hasFile('video_proof')) {
            $signatureFile = $request->file('video_proof');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/video_proof'), $filename);
            $doctorData['video_proof'] = url('uploads/doctor/video_proof/' . $filename);
        }
        if ($request->hasFile('image')) {
            $signatureFile = $request->file('image');
            $filename = time() . uniqid() . '.' . $signatureFile->getClientOriginalExtension();
            $signatureFile->move(public_path('uploads/doctor/image'), $filename);
            $doctorData['image'] = url('uploads/doctor/image/' . $filename);

            UserTable::where('id', $user->id)->update(['image' => url('uploads/doctor/image/' . $filename)]);
        }

        $getlastid = Common::getlastid('doctor');
        // check  if the unique id is blank
        $unique_id = empty($getlastid->unique_id) ? 'MYPHYSIO-100' : '';


        // update or create doctor info
        if (!empty($doctorData)) {
            // assign unique id
            if (!empty($unique_id)) {
                $doctorData['unique_id'] = $unique_id;
            }

            $doctor = Doctor::updateOrCreate(
                ['doctor_id' => $user->id],
                $doctorData
            );
        } else {
            $doctor = Doctor::where('doctor_id', $user->id)->first(); // just fetch if no update
        }
        return response()->json([
            'code' => 200,
            'message' => 'Doctor profile updated successfully.',
            'data' => [
                'doctor' => $doctor
            ]
        ]);
    }

    public function states(Request $request)
    {
        // Fetch distinct states from cities table
        $states = DB::table('cities')
            ->select('city_state')
            ->distinct()
            ->pluck('city_state')
            ->map(fn($state) => trim($state)) // remove spaces
            ->unique()
            ->values();

        // States to exclude
        $excludedStates = [
            'Agra',
            'Bulandshahr',
            'Farrukhabad',
            'Ghazipur',
            'Hardoi',
            'India',
            'Purulia',
            'Rampur'
        ];

        // Filter unwanted
        $filteredStates = $states->reject(function ($state) use ($excludedStates) {
            return in_array($state, $excludedStates);
        })->values();

        if ($filteredStates->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No valid states found.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'count' => $filteredStates->count(),
            'states' => $filteredStates
        ], 200);
    }


    public function getCitiesByState($state)
    {
        // Clean state value (avoid case issues)
        $state = trim($state);

        $cities = DB::table('cities')
            ->whereRaw('LOWER(city_state) = ?', [strtolower($state)]) // case-insensitive
            ->select('city_id', 'city_name')
            ->orderBy('city_name')
            ->get();

        if ($cities->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => "No cities found for state: {$state}."
            ], 404);
        }

        return response()->json([
            'status' => true,
            'state' => $state,
            'count' => $cities->count(),
            'cities' => $cities
        ], 200);
    }



    public function CategoryLevelWiseDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:category,id',
            'level' => 'required|string',
            'session_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $genre = $request->input('role', 'doctor');
        $levelText = $request->level;
        $sessionType = $request->session_type;
        $levelNumber = (int) filter_var($levelText, FILTER_SANITIZE_NUMBER_INT); // "Level 2" → 2

        // Subquery for reviews (avg + count)
        $reviewsSub = DB::table('reviews')
            ->select(
                'doctor_id',
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(id) as total_reviews')
            )
            ->groupBy('doctor_id');

        // Main query using Query Builder to join review stats
        $query = DB::table('user_table')
            ->leftJoin('doctor', 'doctor.doctor_id', '=', 'user_table.id')
            ->leftJoinSub($reviewsSub, 'reviews', 'reviews.doctor_id', '=', 'user_table.id')
            ->where('user_table.genre', $genre)
            ->where('user_table.level', $levelText)
            ->where('doctor.category_id', $request->category_id)
            ->select(
                'user_table.id',
                'user_table.name',
                'user_table.email',
                'user_table.phone',
                'user_table.level',
                'user_table.image',
                'doctor.unique_id',
                'doctor.fees',
                'doctor.gender',
                'doctor.description',
                'doctor.degree',
                'doctor.experience_year',
                'doctor.category_id',
                'reviews.avg_rating',
                'reviews.total_reviews'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_table.name', 'like', "%$search%")
                    ->orWhere('user_table.email', 'like', "%$search%")
                    ->orWhere('doctor.degree', 'like', "%$search%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $doctors = $query->orderByDesc('user_table.id')->paginate($perPage);

        if ($doctors->isEmpty()) {
            return response()->json(['code' => 404, 'message' => 'No Doctors Found'], 404);
        }

        // Fetch single level price based on category_id + level + session_type
        $priceItem = CategoryLevel::where('category_id', $request->category_id)
            ->where('level', $levelNumber)
            ->where('session_type', $sessionType)
            ->first();

        $responseData = $doctors->map(function ($doctor) use ($priceItem) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'level' => $doctor->level,
                'image' => $doctor->image ? $doctor->image : null,
                'unique_id' => $doctor->unique_id,
                'description' => $doctor->description,
                'fees' => $doctor->fees,
                'degree' => json_decode($doctor->degree ?? '[]'),
                'experience_year' => $doctor->experience_year,
                'gender' => $doctor->gender,
                'avg_rating' => round($doctor->avg_rating ?? 0, 1),
                'total_reviews' => $doctor->total_reviews ?? 0,
                'level_price' => $priceItem ? [
                    'session_type' => $priceItem->session_type,
                    'sessions' => $priceItem->sessions,
                    'price' => $priceItem->price,
                ] : null,
            ];
        });

        return response()->json([
            'code' => 200,
            'message' => 'Doctors found successfully.',
            'data' => $responseData,
            'pagination' => [
                'current_page' => $doctors->currentPage(),
                'per_page' => $doctors->perPage(),
                'total' => $doctors->total(),
                'last_page' => $doctors->lastPage(),
            ],
        ]);
    }


    public function generateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'nullable|exists:bookings,id',
            'doctor_id' => 'required|integer|exists:user_table,id',
            'user_id' => 'required|integer|exists:user_table,id',
            'session_type' => 'required|string',
            'slots' => 'required|array|min:1',
            'slots.*' => 'required|integer|exists:timeslots,id',
            'note' => 'nullable|string',
            'name' => 'nullable|string',
            'gender' => 'nullable|string',
            'age' => 'nullable|string',
            'level' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:category,id',
            'level_amount' => 'nullable|numeric',
            'address_id' => 'nullable|integer|exists:user_address,id',

            // Payment fields
            'payment_status' => 'nullable|string',
            'payment_detail' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $levelamount = 0;
        if ($request->filled('category_id') && $request->filled('level')) {
            $amount = CategoryLevel::where('category_id', $request->category_id)
                ->where('level', $request->level)
                ->where('session_type', strtolower($request->session_type))
                ->first();

            $levelamount = $amount ? $amount->price : 0;
        }

        $data = [
            'user_id' => $request->user_id,
            'doctor_id' => $request->doctor_id,
            'address_id' => $request->address_id,
            'slots' => json_encode($request->slots),
            'session_type' => $request->session_type,
            'note' => $request->note,
            'name' => $request->name,
            'level_amount' => $levelamount,
            'gender' => $request->gender,
            'level' => $request->level,
            'age' => $request->age,
            'category_id' => $request->category_id,
        ];

        // Correct models: both doctor and patient from user_table
        $doctorInfo = UserTable::with('doctor')->find($request->doctor_id);
        $userInfo = UserTable::find($request->user_id);
        $slotInfo = DB::table('timeslots')->whereIn('id', $request->slots)->get();
        $categoryInfo = Category::find($request->category_id);

        if ($request->filled('order_id')) {
            // Update booking
            $booking = Booking::find($request->order_id);
            $booking->update($data);

            BookingPayment::updateOrCreate(
                ['order_id' => $booking->id],
                [
                    'status' => $request->payment_status ?? 'pending',
                    'payment_detail' => json_encode($request->payment_detail),
                ]
            );

            return response()->json([
                'code' => 200,
                'message' => 'Booking updated with payment.',
                'data' => $booking,
                'slots' => $slotInfo,
                'doctor' => $doctorInfo,
                'category' => $categoryInfo,
            ]);
        } else {
            // New booking
            $data['booking_id'] = 'BOOK-' . now()->format('ymdHis') . '-' . rand(1000, 9999);
            $booking = Booking::create($data);

            $this->generateOrderTransaction(
                $booking->id,
                $request->user_id,
                $request->payment_status,
                $request->payment_detail
            );

            if (!empty($request->payment_detail)) {
                BookingPayment::create([
                    'order_id' => $booking->id,
                    'status' => $request->payment_status ?? 'pending',
                    'payment_detail' => json_encode($request->payment_detail),
                ]);
            }

            // Mark slots booked
            DB::table('timeslots')
                ->whereIn('id', $request->slots)
                ->update([
                    'isBooked' => 1,
                    'bookedBy' => $request->user_id,
                    'booked_date' => now(),
                ]);

            // Notifications
            foreach ($slotInfo as $slot) {
                $appointmentDate = $slot->date;
                $appointmentTime = $slot->start_time . ' - ' . $slot->end_time;
                $doctorName = $doctorInfo->name ?? 'Doctor';
                $patientName = $userInfo->name ?? 'Patient';

                // notify user
                DB::table('notifications')->insert([
                    'user_id' => $userInfo->id,
                    'message' => "Hello, your appointment with $doctorName has been successfully booked for $appointmentDate at $appointmentTime.",
                    'corresponding_user' => $doctorInfo->id,
                    'created_at' => now()
                ]);

                // notify doctor
                DB::table('notifications')->insert([
                    'user_id' => $doctorInfo->id,
                    'message' => "Hello Dr. $doctorName, you have a new appointment with $patientName on $appointmentDate at $appointmentTime.",
                    'corresponding_user' => $userInfo->id,
                    'created_at' => now()
                ]);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Booking created with payment.',
                'data' => $booking,
                'slots' => $slotInfo,
                'doctor' => $doctorInfo,
                'category' => $categoryInfo,
            ]);
        }
    }


    public function generateOrderTransaction($bookingId, $userId, $paymentStatus, $paymentDetail)
    {
        DB::table('transactions')->insert([
            'user_id' => $userId,
            'type' => 'booking',
            'amount' => $paymentDetail['amount'] ?? null,
            'payment_id' => $paymentDetail['payment_id'] ?? null,
            'status' => $paymentStatus ?? 'pending',
            'details' => json_encode($paymentDetail),
            'created_at' => now(),
        ]);
    }


    // get user bookings
    public function getUserBookings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_table,id',
            'type' => 'nullable|in:upcoming,completed,today,previous,rescheduled'

        ]);
        $type = [];

        $type = $request->type;
        $userId = $request->user_id;

        $bookings = Booking::where('user_id', $userId)
            ->with([
                'doctorInfo:id,name,email,phone,image',
                'address:id,type,city,state,zip_code,full_address,is_selected',
                'payment'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                $slots = collect(json_decode($booking->slots))->map(function ($slotId) {
                    return DB::table('timeslots')
                        ->where('id', $slotId)
                        ->select('id', 'start_time', 'end_time', 'date', 'iscompleted')
                        ->first();
                })->filter()->values();

                return [
                    'booking_id' => $booking->booking_id,
                    'session_type' => $booking->session_type,
                    'note' => $booking->note,
                    'name' => $booking->name,
                    'gender' => $booking->gender,
                    'age' => $booking->age,
                    'category_id' => $booking->category_id,
                    'level' => $booking->level,
                    'level_amount' => $booking->level_amount,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                    'doctor' => $booking->doctorInfo ? [
                        'id' => $booking->doctorInfo->id,
                        'name' => $booking->doctorInfo->name,
                        'email' => $booking->doctorInfo->email,
                        'phone' => $booking->doctorInfo->phone,
                        'image' => $booking->doctorInfo->image,
                    ] : null,
                    'payment' => $booking->payment ? [
                        'status' => $booking->payment->status,
                        'detail' => json_decode($booking->payment->payment_detail ?? '[]', true),
                    ] : null,
                    'address' => $booking->address ? [
                        'type' => $booking->address->type,
                        'city' => $booking->address->city,
                        'state' => $booking->address->state,
                        'zip_code' => $booking->address->zip_code,
                        'full_address' => $booking->address->full_address,
                        'is_selected' => $booking->address->is_selected,
                    ] : null,
                    'slots' => $slots,
                    'status_type' => $this->getBookingStatus($slots),
                ];
            });

        // Filter based on requested type
        if ($type) {
            $today = now()->format('Y-m-d');
            $bookings = $bookings->filter(function ($booking) use ($type, $today) {
                $dates = collect($booking['slots'])->pluck('date')->unique();

                switch ($type) {
                    case 'today':
                        return $dates->contains($today);
                    case 'upcoming':
                        return $dates->filter(fn($d) => $d > $today)->isNotEmpty();
                    case 'completed':
                        return collect($booking['slots'])->every(fn($s) => $s->iscompleted == 1);
                    case 'previous':
                        return $dates->every(fn($d) => $d < $today);
                    case 'rescheduled':
                        return $booking['note'] && str_contains(strtolower($booking['note']), 'reschedule');
                    default:
                        return true;
                }
            })->values();
        }

        return response()->json([
            'code' => 200,
            'message' => 'User bookings fetched successfully.',
            'data' => $bookings,
        ]);
    }

    private function getBookingStatus($slots)
    {
        $today = now()->format('Y-m-d');
        $dates = collect($slots)->pluck('date')->unique();

        if ($dates->contains($today))
            return 'today';
        if ($dates->every(fn($d) => $d < $today))
            return 'previous';
        if (collect($slots)->every(fn($s) => $s->iscompleted == 1))
            return 'completed';
        if ($dates->filter(fn($d) => $d > $today)->isNotEmpty())
            return 'upcoming';

        return 'unknown';
    }

    public function getBookingById($id)
    {
        $booking = Booking::with([
            'doctorInfo:id,name,email,phone,image',
            'address:id,type,city,state,zip_code,full_address,is_selected',
            'payment'
        ])->find($id);

        if (!$booking) {
            return response()->json([
                'code' => 404,
                'message' => 'Booking not found.',
            ], 404);
        }

        // Decode and fetch slot details
        $slots = collect(json_decode($booking->slots))->map(function ($slotId) {
            return DB::table('timeslots')
                ->where('id', $slotId)
                ->select('id', 'start_time', 'end_time', 'date', 'iscompleted')
                ->first();
        })->filter()->values();

        return response()->json([
            'code' => 200,
            'message' => 'Booking details fetched successfully.',
            'data' => [
                'booking_id' => $booking->booking_id,
                'session_type' => $booking->session_type,
                'note' => $booking->note,
                'name' => $booking->name,
                'gender' => $booking->gender,
                'age' => $booking->age,
                'category_id' => $booking->category_id,
                'level' => $booking->level,
                'level_amount' => $booking->level_amount,
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),

                // Doctor Info
                'doctor' => $booking->doctorInfo ? [
                    'id' => $booking->doctorInfo->id,
                    'name' => $booking->doctorInfo->name,
                    'email' => $booking->doctorInfo->email,
                    'phone' => $booking->doctorInfo->phone,
                    'image' => $booking->doctorInfo->image,
                ] : null,

                // Payment Info
                'payment' => $booking->payment ? [
                    'status' => $booking->payment->status,
                    'detail' => json_decode($booking->payment->payment_detail ?? '[]', true),
                ] : null,

                // Address Info
                'address' => $booking->address ? [
                    'type' => $booking->address->type,
                    'city' => $booking->address->city,
                    'state' => $booking->address->state,
                    'zip_code' => $booking->address->zip_code,
                    'full_address' => $booking->address->full_address,
                    'is_selected' => $booking->address->is_selected,
                ] : null,

                // Slot Info
                'slots' => $slots,
            ]
        ]);
    }



    public function getDocterBookings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:user_table,id',
            'type' => 'nullable|in:upcoming,completed,today,previous,rescheduled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $doctorId = $request->doctor_id;
        $type = $request->type;
        $today = now()->format('Y-m-d');

        $bookings = Booking::where('doctor_id', $doctorId)
            ->with([
                'userInfo:id,name,email,phone',
                'payment',
                'address:id,type,city,state,zip_code,full_address,is_selected'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                $slots = collect(json_decode($booking->slots))->map(function ($slotId) {
                    return DB::table('timeslots')
                        ->where('id', $slotId)
                        ->select('id', 'start_time', 'end_time', 'date', 'iscompleted')
                        ->first();
                })->filter()->values();

                return [
                    'booking_id' => $booking->booking_id,
                    'status' => $booking->status,
                    'note' => $booking->note,
                    'session_type' => $booking->session_type,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                    'user' => $booking->userInfo,
                    'payment' => $booking->payment ? [
                        'status' => $booking->payment->status,
                        'detail' => json_decode($booking->payment->payment_detail ?? '{}', true)
                    ] : null,
                    'address' => $booking->address,
                    'slots' => $slots,
                    'status_type' => $this->resolveBookingStatus($slots)
                ];
            });

        if ($type) {
            $bookings = $bookings->filter(function ($booking) use ($type, $today) {
                $dates = collect($booking['slots'])->pluck('date')->unique();

                switch ($type) {
                    case 'today':
                        return $dates->contains($today);
                    case 'upcoming':
                        return $dates->filter(fn($d) => $d > $today)->isNotEmpty();
                    case 'completed':
                        return collect($booking['slots'])->every(fn($s) => $s->iscompleted == 1);
                    case 'previous':
                        return $dates->every(fn($d) => $d < $today);
                    case 'rescheduled':
                        return $booking['note'] && str_contains(strtolower($booking['note']), 'reschedule');
                    default:
                        return true;
                }
            })->values();
        }

        return response()->json([
            'code' => 200,
            'message' => 'Bookings fetched successfully.',
            'data' => $bookings
        ]);
    }

    private function resolveBookingStatus($slots)
    {
        $today = now()->format('Y-m-d');
        $dates = collect($slots)->pluck('date')->unique();

        if ($dates->contains($today))
            return 'today';
        if ($dates->every(fn($d) => $d < $today))
            return 'previous';
        if (collect($slots)->every(fn($s) => $s->iscompleted == 1))
            return 'completed';
        if ($dates->filter(fn($d) => $d > $today)->isNotEmpty())
            return 'upcoming';

        return 'unknown';
    }


    public function getPatientDetails(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:user_table,id',
            'user_id' => 'required|exists:user_table,id'
        ]);

        $user_id = $request->user_id;
        $user = DB::table('user_table')->where('id', $user_id)->select('id', 'name', 'email', 'image')->first();

        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found'
            ], 404);
        }

        $address = DB::table('user_address')->where('user_id', $user_id)->first();

        $bookingHistory = DB::table('bookings')
            ->where('user_id', $user_id)
            ->where('doctor_id', $request->doctor_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                $slots = collect(json_decode($booking->slots))->map(function ($slotId) {
                    return DB::table('timeslots')
                        ->where('id', $slotId)
                        ->select('id', 'start_time', 'end_time', 'date', 'iscompleted')
                        ->first();
                })->filter()->values();

                return [
                    'booking_id' => $booking->booking_id,
                    'session_type' => $booking->session_type,
                    'created_at' => $booking->created_at,
                    'slots' => $slots,
                    'note' => $booking->note
                ];
            });

        return response()->json([
            'code' => 200,
            'data' => [
                'user' => $user,
                'address' => $address,
                'history' => $bookingHistory
            ]
        ]);
    }


    public function getTransactions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_table,id'
        ]);

        $transactions = DB::table('transactions')
            ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'Transactions fetched successfully.',
            'data' => $transactions
        ]);
    }
}
