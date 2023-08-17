<?php

use App\Http\Controllers\Api\Seller\AuthController;
use App\Http\Controllers\Api\Seller\ProductController;
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
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::middleware("isApiSeller")->prefix("seller")->group(function(){

    Route::get('/',[ProductController::class,'index']);
    Route::get('/show/{id}',[ProductController::class,'show']);
    Route::post('/store',[ProductController::class,'store']);
    Route::post('/update/{id}',[ProductController::class,'update']);
    Route::get('/delete/{id}',[ProductController::class,'delete']);

    Route::post('/logout',[AuthController::class,'logout']);

});


