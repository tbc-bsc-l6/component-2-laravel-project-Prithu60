<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;


Route::get('/', function () {
    return view('welcome');
});

// Student enroll route
Route::post('/modules/{module}/enroll', [StudentEnrollmentController::class, 'enroll'])
    ->middleware(['auth'])
    ->name('modules.enroll');

Route::get('/student/modules/history', [StudentModuleController::class, 'history'])
    ->middleware(['auth'])
    ->name('student.modules.history');


// Admin create module
Route::post('/admin/modules', [AdminModuleController::class, 'store'])
    ->middleware(['auth'])
    ->name('admin.modules.store');

// Admin assign teacher
Route::post('/admin/modules/{module}/assign-teacher', [AdminModuleController::class, 'assignTeacher'])
    ->middleware(['auth'])
    ->name('admin.modules.assignTeacher');

Route::post('/admin/modules/{module}/toggle-availability', [AdminModuleController::class, 'toggleAvailability'])
    ->middleware(['auth'])
    ->name('admin.modules.toggleAvailability');
    
//  GET request to fetch all modules assigned to the logged-in teacher
Route::get('/teacher/modules', [TeacherModuleController::class, 'index'])
    ->middleware(['auth'])
    ->name('teacher.modules.index');

Route::get('/teacher/modules/{module}/students', [TeacherModuleController::class, 'students'])
    ->middleware(['auth'])
    ->name('teacher.modules.students');

Route::post('/teacher/modules/{module}/result', [TeacherModuleController::class, 'setResult'])
    ->middleware(['auth'])
    ->name('teacher.modules.result');
