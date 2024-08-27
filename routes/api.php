<?php

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

Route::middleware(['loadData'])->get('/app/load-data', [\App\Http\Controllers\Api\AppController::class, 'index']);

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/register/check-user', [\App\Http\Controllers\AuthController::class, 'checkUser']);
Route::post('/register/upload-file', [\App\Http\Controllers\AuthController::class, 'upload']);

Route::post('/forgot-password', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [\App\Http\Controllers\AuthController::class, 'resetPassword']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::resource('results', '\App\Http\Controllers\Api\ResultController')->only(['index', 'show']);

    Route::get('/user/show', [\App\Http\Controllers\Api\UserController::class, 'show']);

    Route::resource('drivers', '\App\Http\Controllers\Api\DriverController')->only(['index', 'store', 'destroy', 'update', 'show']);

    Route::post('orders/{order}/payment/create/', [\App\Http\Controllers\Api\PaymentController::class, 'createCheckoutSession']);

    Route::post('orders/{order}/upload', '\App\Http\Controllers\Api\OrderController@uploadDocument');
    Route::get('orders/{order}/documents', '\App\Http\Controllers\Api\OrderController@documents');
    Route::delete('orders/{order}/documents/{document}', [\App\Http\Controllers\Api\OrderController::class, 'destroyDocument']);
    Route::get('orders/{order}/documents/download/{document}', [\App\Http\Controllers\Api\OrderController::class, 'downloadDocument']);

    Route::resource('orders', '\App\Http\Controllers\Api\OrderController')->only(['index', 'store', 'show', 'update', 'destroy']);

    Route::post('payment/create/', [\App\Http\Controllers\Api\PaymentController::class, 'createCheckoutSession']);

});

Route::group(['middleware' => ['auth:sanctum', 'dashboard'], 'prefix' => 'admin'], function ()
{

    Route::post('orders/{order}/upload', '\App\Http\Controllers\Api\Admin\OrderController@uploadDocument');
    Route::get('orders/{order}/documents', '\App\Http\Controllers\Api\Admin\OrderController@documents');
    Route::delete('orders/{order}/documents/{document}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'destroyDocument']);
    Route::get('orders/{order}/documents/download/{document}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'downloadDocument']);

    Route::resource('orders', '\App\Http\Controllers\Api\Admin\OrderController')->only(['index', 'show', 'update', 'destroy']);


    Route::get('users/{user}/orders', [\App\Http\Controllers\Api\Admin\UserController::class, 'orders']);
    Route::resource('users', '\App\Http\Controllers\Api\Admin\UserController')->only(['index', 'show']);



});

Route::group(['middleware' => ['auth:sanctum', 'admin'], 'prefix' => 'admin'], function ()
{

    Route::put('users/{user}', [\App\Http\Controllers\Api\Admin\UserController::class, 'update']);
    Route::delete('users/{user}', [\App\Http\Controllers\Api\Admin\UserController::class, 'destroy']);
    Route::post('users', [\App\Http\Controllers\Api\Admin\UserController::class, 'store']);

    Route::get('users/managers/all', [\App\Http\Controllers\Api\Admin\UserController::class, 'managers']);
    Route::post('users/managers/reattach/{userFrom}/{userTo}', [\App\Http\Controllers\Api\Admin\UserController::class, 'reattach']);
    Route::post('users/managers/attach/{manager}/{user}', [\App\Http\Controllers\Api\Admin\UserController::class, 'attach']);


});
