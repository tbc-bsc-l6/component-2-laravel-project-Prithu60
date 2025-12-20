<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;

Route::get('/', function () {
    return view('welcome');
});

// Student enroll route
Route::post('/modules/{module}/enroll', [StudentEnrollmentController::class, 'enroll'])
    ->middleware(['auth'])
    ->name('modules.enroll');

// Admin create module
Route::post('/admin/modules', [AdminModuleController::class, 'store'])
    ->middleware(['auth'])
    ->name('admin.modules.store');

// Admin assign teacher
Route::post('/admin/modules/{module}/assign-teacher', [AdminModuleController::class, 'assignTeacher'])
    ->middleware(['auth'])
    ->name('admin.modules.assignTeacher');
