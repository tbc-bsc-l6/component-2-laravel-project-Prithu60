<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;

use App\Http\Controllers\Admin\ModuleController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (ROLE BASED)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $role = auth()->user()?->role?->name;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        'old_student' => redirect()->route('student.modules.history'),
        default => redirect('/'),
    };
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (ADMIN ONLY)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('modules', ModuleController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Modules
    Route::get('/admin/modules', [AdminModuleController::class, 'index'])
        ->name('admin.modules.index');

    Route::post('/admin/modules', [AdminModuleController::class, 'store'])
        ->name('admin.modules.store');

    Route::post('/admin/modules/{module}/assign-teacher', [AdminModuleController::class, 'assignTeacher'])
        ->name('admin.modules.assignTeacher');

    Route::post('/admin/modules/{module}/toggle-availability', [AdminModuleController::class, 'toggleAvailability'])
        ->name('admin.modules.toggleAvailability');

    // Teachers
    Route::get('/admin/teachers', [TeacherController::class, 'index'])
        ->name('admin.teachers.index');

    Route::post('/admin/teachers', [TeacherController::class, 'store'])
        ->name('admin.teachers.store');

    Route::delete('/admin/teachers/{user}', [TeacherController::class, 'destroy'])
        ->name('admin.teachers.destroy');

    // Students
    Route::get('/admin/students', [AdminStudentController::class, 'index'])
        ->name('admin.students.index');
});

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:teacher'])->group(function () {

    Route::get('/teacher/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');

    Route::get('/teacher/modules', [TeacherModuleController::class, 'index'])
        ->name('teacher.modules.index');

    Route::get('/teacher/modules/{module}/students', [TeacherModuleController::class, 'students'])
        ->name('teacher.modules.students');

    Route::post('/teacher/modules/{module}/result', [TeacherModuleController::class, 'setResult'])
        ->name('teacher.modules.result');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student,old_student'])->group(function () {

    Route::post('/modules/{module}/enroll', [StudentEnrollmentController::class, 'enroll'])
        ->name('modules.enroll');

    Route::get('/student/modules/history', [StudentModuleController::class, 'history'])
        ->name('student.modules.history');

    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});

/*
|--------------------------------------------------------------------------
| PROFILE (REQUIRED BY BREEZE)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
