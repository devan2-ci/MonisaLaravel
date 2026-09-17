<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Parent\AttendanceController as ParentAttendanceController;
use App\Http\Controllers\Api\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Api\Teacher\AttendanceController as TeacherAttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('welcome', function(){
    return response()->json([
        'success' => true,
        'message' => 'Welcome to API v1 Monisa'
    ]);
});

Route::prefix('auth')->group(function(){
    // Core Authentication
    Route::post('signup', [AuthController::class, 'signUp'])->name('signup');
    Route::post('signin', [AuthController::class, 'signIn'])->name('signin');
    Route::post('signout', [AuthController::class, 'signout'])->middleware('auth:sanctum')->name('signout');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum')->name('profile');
});

Route::prefix('student')->group(function(){
    // Core Authentication
    Route::get('attendances',[StudentAttendanceController::class, 'index'])->middleware('auth:sanctum')->name('attendance.index');
    Route::post('attendances',[StudentAttendanceController::class, 'store'])->middleware('auth:sanctum')->name('attendance.store');
});

Route::prefix('teacher')->group(function(){
    // Core Authentication
    Route::get('attendances',[TeacherAttendanceController::class, 'index'])->middleware('auth:sanctum')->name('attendance.index');
});

Route::prefix('parent')->group(function(){
    // Core Authentication
    Route::get('attendances',[ParentAttendanceController::class, 'index'])->middleware('auth:sanctum')->name('attendance.index');
    Route::get('attendances/latest',[ParentAttendanceController::class, 'latest'])->middleware('auth:sanctum')->name('attendance.latest');
});