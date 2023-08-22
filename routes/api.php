<?php

use App\Http\Controllers\Api\CateController;
use App\Http\Controllers\Api\Seller\AuthController;
use App\Http\Controllers\Api\User\UserAuthController;
use App\Http\Controllers\Api\Seller\CoponController;
use App\Http\Controllers\Api\Seller\EventController;
use App\Http\Controllers\Api\Seller\ProductController;
use App\Http\Controllers\Api\Seller\ProfileController;
use App\Http\Controllers\Api\User\UserAddressController;
use App\Http\Controllers\Api\User\UserCoponController;
use App\Http\Controllers\Api\User\UserEventController;
use App\Http\Controllers\Api\User\UserProductController;
use App\Http\Controllers\Api\User\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// seller
Route::prefix('seller')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware("isApiSeller")->group(function(){
    // Product
        Route::get('/product',[ProductController::class,'index']);
        Route::get('/product-show/{id}',[ProductController::class,'show']);
        Route::post('/product-store',[ProductController::class,'store']);
        Route::post('/product-update/{id}',[ProductController::class,'update']);
        Route::get('/product-delete/{id}',[ProductController::class,'delete']);

    // Event
        Route::get('/event',[EventController::class,'index']);
        Route::get('/event-show/{id}',[EventController::class,'show']);
        Route::post('/event-store',[EventController::class,'store']);
        Route::post('/event-update/{id}',[EventController::class,'update']);
        Route::get('/event-delete/{id}',[EventController::class,'delete']);


    // Copon
        Route::get('/copon',[CoponController::class,'index']);
        Route::get('/copon-show/{id}',[CoponController::class,'show']);
        Route::post('/copon-store',[CoponController::class,'store']);
        Route::post('/copon-update/{id}',[CoponController::class,'update']);
        Route::get('/copon-delete/{id}',[CoponController::class,'delete']);

    //profile
        Route::post('/profile-update',[ProfileController::class,'update']);

    // logout
        Route::post('/logout',[AuthController::class,'logout']);

    });
});

Route::prefix('user')->group(function () {
    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware("isApiUser")->group(function(){
         //profile
         Route::post('/profile-update',[UserProfileController::class,'update']);
         //address
        Route::get('/address',[UserAddressController::class,'index']);
        Route::get('/address-show/{id}',[UserAddressController::class,'show']);
        Route::post('/address-create',[UserAddressController::class,'store']);
        Route::post('/address-update/{id}',[UserAddressController::class,'update']);
        Route::get('/address-delete/{id}',[UserAddressController::class,'delete']);

        // Product
        Route::get('/product',[UserProductController::class,'all_product']);
        Route::get('/product/{id}',[UserProductController::class,'one_product']);

        // Event
        Route::get('/event',[UserEventController::class,'all_event']);
        Route::get('/event/{id}',[UserEventController::class,'one_event']);

        // Copon
        Route::get('/copon',[UserCoponController::class,'all_copon']);
        Route::get('/copon/{id}',[UserCoponController::class,'one_copon']);
        // logout
        Route::post('/logout',[UserAuthController::class,'logout']);

    });
});
Route::get('/all_cate',[CateController::class,'all_cate']);



