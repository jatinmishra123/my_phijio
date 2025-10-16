<?php

use App\Http\Controllers\admin\ApiController;
use App\Http\Controllers\admin\DoctorController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\admin\CustomerController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create-account', [ApiController::class, 'createaccount']);
Route::post('/login', [ApiController::class, 'login']);
Route::post('/otplogin', [ApiController::class, 'otplogin']);
Route::post('/otplogin1', [ApiController::class, 'checkOtpVerifyNew']);
Route::get('/getuserinfo', [ApiController::class, 'getUser']);
Route::post('/verifyotp', [ApiController::class, 'verifyotp']);
Route::post('/update-password', [ApiController::class, 'updatePassword']);


Route::post('/doctor-complete-profile', [ApiController::class, 'completeprofile']);
Route::post('/create-slot', [ApiController::class, 'createSlot']);
Route::post('/update-slot', [ApiController::class, 'updateSlot']);

Route::post('/delete-slot', [ApiController::class, 'deleteSlot']);

Route::post('/get-all-slots', [ApiController::class, 'getAllSlots']);
Route::get('/get-categories', [ApiController::class, 'getCategories']);
Route::get('/get-allcategories', [ApiController::class, 'getallcat']);

Route::post('/get-reviews/', [ApiController::class, 'getReviews']);


Route::post('/submit-review', [ApiController::class, 'submitReview']);

Route::post('/book-appoinment', [ApiController::class, 'BookAppoinment']);
Route::post('/add-address', [ApiController::class, 'addAddress']);
Route::post('/delete-address', [ApiController::class, 'deleteAddress']);
// updateAddress
Route::post('/update-address', [ApiController::class, 'updateAddress']);
Route::post('/fetch-address', [ApiController::class, 'fetchAddress']);
Route::get('/doctors-list', [ApiController::class, 'GetDoctorsList']);
Route::get('/get-doctor-by-id/{id}', [ApiController::class, 'getDoctorById']);
Route::get('/get-all-bookings', [ApiController::class, 'getAllBookings']);
Route::post('/appointment-completed', [ApiController::class, 'markCompleted']);

Route::post('/mark-address-selected', [ApiController::class, 'markAddressSelected']);
Route::post('/getbanners', action: [ApiController::class, 'getbanners']);

Route::post('/get-selected-address', [ApiController::class, 'getselectedAddress']);

Route::get('/get-all-chats', [ApiController::class, 'getAllChatMessages']);
Route::get('/get-inner-chats', [ApiController::class, 'getInnerChat']);

Route::get('/get-notifications', [ApiController::class, 'getNotifications']);
Route::post('/uploadprescription', [ApiController::class, 'uploadprescription']);
Route::post('/upload-user-image', [ApiController::class, 'uploadUserImage']);
Route::post('/reshedule-appointment', [ApiController::class, 'reseduleOption']);
Route::post('/forget-password', [ApiController::class, 'otploginForForgetPassword']);
Route::post('/markasread', [ApiController::class, 'updateNotifications']);
Route::get('/notification-count', [ApiController::class, 'getNotificationCount']);
Route::get('/getwallet', [ApiController::class, 'getwallet']);
Route::get('/redeem-request', [ApiController::class, 'redeemRequest']);
Route::get('/transaction', [ApiController::class, 'transactions']);
Route::get('/getallsection', [ApiController::class, 'getallsection']);
Route::get('/testoverview', [ApiController::class, 'testoverview']);
Route::post('/submit-answer', [ApiController::class, 'submitanswer']);


Route::get('/plans', [ApiController::class, 'getplans']);

// Route::post('/physiotherapist-kit',[ApiController::class,'physiotherapistKit']);
Route::get('/get-physiotherapist-kit', [ApiController::class, 'getPhysiotherapistKit']);
Route::post('/order-plans-details', [ApiController::class, 'orderPlansDetails']);

Route::post('/payment-kit-order', [ApiController::class, 'paymentKitOrder']);
Route::post('/payment-plan-order', [ApiController::class, 'paymentPlanOrder']);
Route::post('/delete-appointment', [ApiController::class, 'deleteAppointment']);

// purchase plan api
Route::post('purchase-plan', [ApiController::class, 'purchasePlan']);
Route::get('user/current-plan', [ApiController::class, 'currentPlan']);



// purchase kits api 
Route::post('/purchase-kit', [ApiController::class, 'orderDetails']);
// submit review for kits 

Route::post('/kit-review', [ApiController::class, 'kitReview']);
Route::get('/basic-info', [ApiController::class, 'basicInfo']);



#qualification api
Route::post('/complete-full-profile', [ApiController::class, 'doctorProfileUpdate']);

Route::get('states', [ApiController::class, 'states']);
Route::get('/states/{state}/cities', [ApiController::class, 'getCitiesByState']);



// doctor list as per level and category
Route::post('/doctors', [ApiController::class, 'CategoryLevelWiseDoctor']);


//create order for bookin g
Route::post('/create-booking', [ApiController::class, 'generateOrder']);
Route::post('/patient/bookings', [ApiController::class, 'getUserBookings']);
Route::get('/booking/{id}', [ApiController::class, 'getBookingById']);
// DOCTOR API 
Route::post('/doctor/bookings', [ApiController::class, 'getDocterBookings']);
Route::post('/doctor/patient', [ApiController::class, 'getPatientDetails']);
Route::get('/doctor/transactions', [ApiController::class, 'getTransactions']);
Route::post('/add-doctor', [DoctorController::class, 'adddoctor']);
Route::post('/doctor/update', [DoctorController::class, 'update']);
// API - Locations
Route::get('locations', [LocationController::class, 'index']);
Route::post('locations/store', [LocationController::class, 'store']);
Route::post('locations/update', [LocationController::class, 'update']);

// API - Degrees
Route::get('degrees', [LocationController::class, 'degreeindex']);
Route::post('degrees/store', [LocationController::class, 'degreestore']);
Route::post('degrees/update', [LocationController::class, 'degreeupdate']);

// API - Certificates
Route::get('certificates', [LocationController::class, 'ceritificatesindex']);
Route::post('certificates/store', [LocationController::class, 'ceritificatestore']);
Route::post('certificates/update', [LocationController::class, 'certificatesupdate']);

// Customers list (JSON return ke liye)
Route::get('/customers', [CustomerController::class, 'index']);

// Update customer password
Route::post('/customers/update-password', [CustomerController::class, 'updatepassword']);
