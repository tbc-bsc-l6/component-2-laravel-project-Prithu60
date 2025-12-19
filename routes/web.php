<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\StudentEnrollmentController;

Route::post('/modules/{module}/enroll', [StudentEnrollmentController::class, 'enroll'])
    ->middleware(['auth'])
    ->name('modules.enroll');

use App\Http\Controllers\Admin\ModuleController as AdminModuleController;

Route::post('/admin/modules', [AdminModuleController::class, 'store'])
    ->middleware(['auth'])
    ->name('admin.modules.store');