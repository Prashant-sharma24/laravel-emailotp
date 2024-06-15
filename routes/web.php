<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
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
    return view('welcome');
});


Route::get('login',[AuthController::class, 'showLoginForm'])->name('login');

Route::post('/send-otp',[AuthController::class, 'sendOtp'])->name('send.otp');

Route::get('/verify-otp',[AuthController::class, 'sendOtpForm'])->name('verify.otp.form');

Route::post('/verify-otp',[AuthController::class, 'verifyOtp'])->name('verify.otp');
