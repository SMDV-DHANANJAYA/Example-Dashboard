<?php

use App\Http\Controllers\API\V1\Auth\AuthAPIController;
use App\Http\Controllers\API\V1\CommonAPIController;
use App\Http\Controllers\API\V1\LocationsAPIController;
use App\Http\Controllers\API\V1\UsersAPIController;
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

Route::middleware('auth:sanctum')->group( function () {

    // auth routes
    Route::withoutMiddleware('auth:sanctum')->group(function () {
        Route::controller(AuthAPIController::class)->group(function(){
            Route::post('login/user', 'loginUser')->name('login.user');

            Route::post('login/admin', 'loginAdmin')->name('login.admin');
        });

        Route::get('check-update',[CommonAPIController::class,'checkUpdate'])->name('check-update');
    });

    // user route
    Route::middleware('abilities:user')->group( function () {
        Route::controller(UsersAPIController::class)->group(function(){
            Route::get('user','getUser')->name('user');

            Route::get('check-work-state','checkWorkState')->name('check-work-state');

            Route::get('work-locations','getLocations')->name('work-locations');

            Route::put('update-user-mobile','updateUser')->name('update-user-mobile');

            Route::post('upload-image','uploadImage')->name('upload-image');

            Route::post('start-work','startWorkLocation')->name('start-work');

            Route::post('stop-work','endWorkLocation')->name('stop-work');
        });

        Route::put('change-fcm-token',[AuthAPIController::class,'changeFCMToken'])->name('change-fcm-token');
    });

    // admin route
    Route::middleware('abilities:admin')->group( function () {
        Route::controller(LocationsAPIController::class)->group(function(){
            Route::get('locations-mobile','locations')->name('locations-mobile');

            Route::get('location/{id}/users','locationUsers')->name('location.users');
        });
    });

    // common routes without ability
    Route::post('logout', [AuthAPIController::class,'logout'])->name('logout-mobile');
});
