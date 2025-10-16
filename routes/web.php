<?php

use App\Http\Controllers\admin\AppointmentController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\DoctorController;
use App\Http\Controllers\admin\HospitalController;
use App\Http\Controllers\admin\IndexController;
use App\Http\Controllers\admin\RegistrationController;
use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\AutoCompleteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PhysiotherapistKitController;
use App\Http\Controllers\OrderContoller;
use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'index']);

// admin panel routes
Route::put('login', [IndexController::class, 'index']);
Route::post('/auth', [IndexController::class, 'check']);
Route::get('/admins', [IndexController::class, 'index']);

Route::group(['middleware' => 'auth', 'prefix' => '{rolebased}'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/change_password', [DashboardController::class, 'change_password']);

    Route::get('/staff/changepassworddiv/{id}/{table}', [StaffController::class, 'changepassworddiv']);
    Route::post('/customer/updatepassword', [StaffController::class, 'updatepassword']);

    Route::get('/registration', [RegistrationController::class, 'index']);
    Route::get('/questions', [RegistrationController::class, 'questions']);
    Route::get('/registration/add', [RegistrationController::class, 'addnew']);
    Route::post('/registration/store', [RegistrationController::class, 'store'])->name('userregisteration');
    Route::get('/registration/edit/{id}', [RegistrationController::class, 'edit']);
    Route::get('/registration/profile/{id}', [RegistrationController::class, 'profile']);
    Route::post('/users/update', [RegistrationController::class, 'update']);

    Route::get('/hospital', [HospitalController::class, 'index']);
    Route::get('/hospital/add', [HospitalController::class, 'addnew']);
    Route::post('/hospital/addhospital', [HospitalController::class, 'addhospital']);
    Route::get('/hospital/edit/{id}', [HospitalController::class, 'edit']);
    Route::post('/hospital/update', [HospitalController::class, 'update']);

    Route::get('/role/{role}', [DoctorController::class, 'index']);
    Route::get('/doctors/add', [DoctorController::class, 'addnew']);
    Route::post('/doctors/adddoctor', [DoctorController::class, 'adddoctor']);
    Route::get('/doctors/profile/{id}', [DoctorController::class, 'profile']);
    Route::get('/doctors/edit/{id}', [DoctorController::class, 'edit']);
    Route::get('/doctors/allotchamber/{id}', [DoctorController::class, 'allot']);
    Route::post('/doctors/updatechamber', [DoctorController::class, 'updatechamber']);
    Route::post('/doctors/update', [DoctorController::class, 'update'])->name('admin.doctor.update');

    Route::get('/staff', [StaffController::class, 'index']);
    Route::get('/staff/add', [StaffController::class, 'addnew']);
    Route::post('/staff/addstaff', [StaffController::class, 'addstaff']);
    Route::get('/staff/edit/{id}', [StaffController::class, 'edit']);
    Route::get('/staff/profile/{id}', [StaffController::class, 'profile']);
    Route::post('/staff/update', [StaffController::class, 'update']);
    Route::post('/staff/delete', [StaffController::class, 'delete']);

    Route::get('/appointments/{slug}', [AppointmentController::class, 'index'])->where('slug', 'all|pending|completed');
    Route::get('/appointments/add', [AppointmentController::class, 'add']);
    Route::post('/appointments/store', [AppointmentController::class, 'store']);
    Route::get('/appointments/edit/{id}', [AppointmentController::class, 'edit']);
    Route::post('/appointment/update', [AppointmentController::class, 'update']);
    Route::post('/appointment/userinfo', [AppointmentController::class, 'userinfo']);
    Route::post('/appointment/chamberinfo', [AppointmentController::class, 'chamberinfo']);
    Route::post('/appointment/doctorinfo', [AppointmentController::class, 'doctorinfo']);
    Route::post('/appointment/alloteddoctor', [AppointmentController::class, 'alloteddoctor']);

    Route::get('/withdrawel/all', [AppointmentController::class, 'allwithdrawel']);

    // Location
    Route::get('/location', [LocationController::class, 'index']);
    Route::get('/degrees', [LocationController::class, 'degreeindex']);
    Route::get('/certificates', [LocationController::class, 'ceritificatesindex']);

    Route::post('/location/store', [LocationController::class, 'store']);
    Route::post('/degree/store', [LocationController::class, 'degreestore']);
    Route::post('/certificate/store', [LocationController::class, 'ceritificatestore']);
    Route::get('/degree/edit/{id}', [LocationController::class, 'degreeedit']);
    Route::get('/ceritificate/edit/{id}', [LocationController::class, 'ceritificateedit']);
    Route::get('/location/edit/{id}', [LocationController::class, 'edit']);
    Route::post('/location/update', [LocationController::class, 'update']);
    Route::post('/degree/update', [LocationController::class, 'degreeupdate']);
    Route::post('/ceritificate/update', [LocationController::class, 'certificatesupdate']);
    Route::post('/location/delete', [LocationController::class, 'delete']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit']);
    Route::post('/category/update', [CategoryController::class, 'update']);
    Route::post('/category/delete', [CategoryController::class, 'delete']);

    // admin/plans/store
    Route::get('/plans', [PlanController::class, 'index']);
    Route::post('/plans/store', [PlanController::class, 'store']);
    Route::get('/kits', [PhysiotherapistKitController::class, 'index']);
    Route::post('/kits/store', [PhysiotherapistKitController::class, 'store']);
    Route::get('/kit-order-list', [OrderContoller::class, 'index']);
    Route::get('/plan-order-list', [OrderContoller::class, 'planOrder']);




    // Route::get('/category/edit/{id}', [CategoryController::class, 'edit']);
    // Route::post('/category/update', [CategoryController::class, 'update']);
    // Route::post('/category/delete', [CategoryController::class, 'delete']);

    Route::get('/banners', [CategoryController::class, 'bannerindex']);
    Route::post('/banners/store', [CategoryController::class, 'bannerstore']);
    Route::get('/banner/edit/{id}', [CategoryController::class, 'banneredit']);
    Route::post('/banner/update', [CategoryController::class, 'bannerupdate']);
    Route::post('/banner/delete', [CategoryController::class, 'bannerdelete']);

    Route::get('/sub-category', [SubCategoryController::class, 'index']);
    Route::post('/sub-category/store', [SubCategoryController::class, 'store']);
    Route::get('/sub-category/edit/{id}', [SubCategoryController::class, 'edit']);
    Route::post('/sub-category/update', [SubCategoryController::class, 'update']);
    Route::post('/sub-category/delete', [SubCategoryController::class, 'delete']);
});

Route::get('/c', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/admins/logout', function () {
    session()->flush();
    return redirect('/');
});

Route::get('/backupdb', function () {
    $DbName              = 'tacanew';
    $get_all_table_query = "SHOW TABLES ";
    $result              = DB::select(DB::raw($get_all_table_query));

    $prep = "Tables_in_$DbName";
    foreach ($result as $res) {
        $tables[] = $res->$prep;
    }

    $connect = DB::connection()->getPdo();

    $get_all_table_query = "SHOW TABLES";
    $statement           = $connect->prepare($get_all_table_query);
    $statement->execute();
    $result = $statement->fetchAll();

    $output = '';
    foreach ($tables as $table) {
        $show_table_query = "SHOW CREATE TABLE " . $table . "";
        $statement        = $connect->prepare($show_table_query);
        $statement->execute();
        $show_table_result = $statement->fetchAll();

        foreach ($show_table_result as $show_table_row) {
            $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
        }
        $select_query = "SELECT * FROM " . $table . "";
        $statement    = $connect->prepare($select_query);
        $statement->execute();
        $total_row = $statement->rowCount();

        for ($count = 0; $count < $total_row; $count++) {
            $single_result      = $statement->fetch(\PDO::FETCH_ASSOC);
            $table_column_array = array_keys($single_result);
            $table_value_array  = array_values($single_result);
            $output .= "\nINSERT INTO $table (";
            $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
            $output .= "'" . implode("','", $table_value_array) . "');\n";
        }
    }
    $file_name   = 'database_backup_on_' . date('y-m-d') . '.sql';
    $file_handle = fopen($file_name, 'w+');
    fwrite($file_handle, $output);
    fclose($file_handle);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_name));
    ob_clean();
    flush();
    readfile($file_name);
    unlink($file_name);
});

Route::get('/autocomplete-search', [AutoCompleteController::class, 'autocompleteSearch']);
Route::get('/autocomplete-chamber', [AutoCompleteController::class, 'chamber']);
Route::get('/autocomplete-doctor', [AutoCompleteController::class, 'doctors']);

Route::get('test', [AutoCompleteController::class, 't']);
