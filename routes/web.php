<?php

use App\Http\Controllers\DashboardController\AdminController;
use App\Http\Controllers\DashboardController\AttendanceController;
use App\Http\Controllers\DashboardController\HomeController;
use App\Http\Controllers\DashboardController\LocationController;
use App\Http\Controllers\DashboardController\NotificationController;
use App\Http\Controllers\DashboardController\PayrollController;
use App\Http\Controllers\DashboardController\PushNotificationController;
use App\Http\Controllers\DashboardController\SettingController;
use App\Http\Controllers\DashboardController\UserController;
use App\Http\Controllers\DashboardController\UserLocationController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect('login');
});

Auth::routes([
    'register' => false,
    'verify' => false,
    'reset' => false,
]);

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::controller(UserController::class)->group(function (){
        Route::get('/users','usersList')->name('users');

        Route::view('/add-user','user.add-user')->name('add-user');
        Route::post('/save-user','saveUser')->name('save-user');

        Route::get('/delete-user','deleteUser')->name('delete-user');

        Route::get('/change-user-state','changeUserState')->name('change-user-state');

        Route::get('/update-user','updateUserView')->name('update-user');
        Route::post('/update-user-web','updateUser')->name('update-user-web');

        Route::get('/view-user-profile','viewUserProfile')->name('view-user-profile');

        Route::get('/view-user-locations','viewUserLocations')->name('view-user-locations');

        Route::get('/update-user-password','updateUserPasswordView')->name('update-user-password');
        Route::post('/update-user-password-save','updateUserPassword')->name('update-user-password-save');
    });

    Route::controller(AdminController::class)->group(function (){
        Route::get('/admins','adminsList')->name('admins');

        Route::view('/add-admin','admin.add-admin')->name('add-admin');
        Route::post('/save-admin','saveAdmin')->name('save-admin');

        Route::get('/change-admin-state','changeAdminState')->name('change-admin-state');

        Route::get('/delete-admin','deleteAdmin')->name('delete-admin');

        Route::get('/update-admin','updateAdminView')->name('update-admin');
        Route::post('/save-update-admin','updateAdmin')->name('save-update-admin');

        Route::get('/update-admin-password','updateAdminPasswordView')->name('update-admin-password');
        Route::post('/save-update-admin-password','updateAdminPassword')->name('save-update-admin-password');
    });

    Route::controller(LocationController::class)->group(function (){
        Route::get('/locations','locationsList')->name('locations');

        Route::view('/add-location','location.add-location')->name('add-location');
        Route::post('/save-location','saveLocation')->name('save-location');

        Route::get('/delete-location','deleteLocation')->name('delete-location');

        Route::get('/change-location-state','changeLocationState')->name('change-location-state');

        Route::get('/update-location','updateLocationView')->name('update-location');
        Route::post('/save-update-location','updateLocation')->name('save-update-location');

        Route::get('/view-location/{id}','viewLocation')->name('view-location');
    });

    Route::controller(UserLocationController::class)->group(function (){
        Route::get('/delete-user-location','deleteUserLocation')->name('delete-user-location');

        Route::post('/update-user-location','updateUserLocation')->name('update-user-location');

        Route::post('/add-user-location','addUserLocation')->name('add-user-location');
    });

    Route::controller(AttendanceController::class)->group(function (){
        Route::get('/attendances','userAttendance')->name('attendances');

        Route::get('/delete-attendance','deleteOldAttendance')->name('delete-attendance');
    });

    Route::controller(PayrollController::class)->group(function (){
        Route::get('/payrolls','view')->name('payrolls');

        Route::get('/download-payroll-excel','downloadExcel')->name('download-payroll-excel');

        Route::get('/download-payroll-pdf','downloadPdf')->name('download-payroll-pdf');
    });

    Route::controller(SettingController::class)->group(function (){
        Route::get('/settings','view')->name('settings');
        Route::post('/setting','store')->name('setting');
    });

    Route::controller(NotificationController::class)->group(function (){
        Route::get('/notifications-all','viewAll')->name('notifications-all');

        Route::get('/notifications-short','viewShort')->name('notifications-short');

        Route::get('/delete-notifications','deleteOldNotification')->name('delete-notifications');
        Route::get('/delete-all-notifications','deleteAllNotification')->name('delete-all-notifications');
    });

    Route::controller(PushNotificationController::class)->group(function (){
        Route::get('/push-messages','pushMessageView')->name('push-messages');

        Route::post('/push-message-send','sendPushMessage')->name('push-message-send');
    });
});
