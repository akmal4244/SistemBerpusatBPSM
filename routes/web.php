<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserMgtController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Helper;



Route::get('/', function () {
    return redirect()->route('login.form');  // Redirect to login page by default
});
Route::get('login', [AuthenticationController::class, 'login'])->name('login.form');
Route::post('login', [AuthenticationController::class, 'loginUser'])->name('login.user');

Route::get('register', [AuthenticationController::class, 'register_form'])->name('register.form');
Route::post('register', [AuthenticationController::class, 'register_submit'])->name('register.submit');

//first time login reset password
Route::get('password/reset/{token}/{id}', [AuthenticationController::class, 'password_reset'])->name('password.reset');
Route::post('password/reset', [AuthenticationController::class, 'password_store'])->name('password.store');

//forgot password
Route::get('password/forgot', [AuthenticationController::class, 'password_forgot'])->name('password.forgot');
Route::post('password/forgot', [AuthenticationController::class, 'password_forgot_store'])->name('password.forgot.store');
//user reset password
Route::get('password-reset', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password-reset', [ResetPasswordController::class, 'reset'])->name('password.update');



// Protected Route
Route::middleware(['auth', 'prevent.back.history'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('redirect/{to}', [HomeController::class, 'redirect_to'])->name('redirect.token');
    //profile
    Route::get('profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password/reset/{id}', [ProfileController::class, 'password_reset'])->name('profile.password_reset');
    Route::post('profile/password/reset/submit/{id}', [ProfileController::class, 'password_reset_submit'])->name('profile.password_reset.submit');
    
});
Route::middleware(['auth'])->group(function () {    
    Route::get('logout', [AuthenticationController::class, 'logout'])->name('logout');
});

//superadmin
Route::middleware(['auth', 'prevent.back.history', 'superadmin'])->group(function () {
    Route::get('user/mgt/list', [UserMgtController::class, 'list'])->name('user.mgt.list');
    Route::get('user/mgt/edit/{id}', [UserMgtController::class, 'edit'])->name('user.mgt.edit');
    Route::post('user/mgt/edit/{id}', [UserMgtController::class, 'edit_submit'])->name('user.mgt.edit.submit');
});

// Authentication Routes
// Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);


// Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// // Password Reset Routes
// Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');


