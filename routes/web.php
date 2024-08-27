<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {


});

//Route::get('admin/accept-user/{user}', [\App\Http\Controllers\AcceptUserController::class, 'show'])->name('admin.accept.user')->middleware(['web']);
//Route::post('admin/accept-user/{user}', [\App\Http\Controllers\AcceptUserController::class, 'store'])->name('admin.accept.user.store')->middleware(['web']);
