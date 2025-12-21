<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentEnrollmentController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;
use App\Http\Controllers\Admin\TeacherController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

// Student enroll in module
Route::post('/modules/{module}/enroll', [StudentEnrollmentController::class, 'enroll'])
    ->middleware(['auth'])
    ->name('modules.enroll');

// Student completed module history (PASS / FAIL)
Route::get('/student/modules/history', [StudentModuleController::class, 'history'])
    ->middleware(['auth'])
    ->name('student.modules.history');

// Student dashboard
Route::get('/student/dashboard', function () {
    return view('student.dashboard');
})->middleware(['auth'])->name('student.dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

// Admin dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('admin.dashboard');

// Admin create module
Route::post('/admin/modules', [AdminModuleController::class, 'store'])
    ->middleware(['auth'])
    ->name('admin.modules.store');

// Admin assign teacher to module
Route::post('/admin/modules/{module}/assign-teacher', [AdminModuleController::class, 'assignTeacher'])
    ->middleware(['auth'])
    ->name('admin.modules.assignTeacher');

// Admin toggle module availability (archive / unarchive)
Route::post('/admin/modules/{module}/toggle-availability', [AdminModuleController::class, 'toggleAvailability'])
    ->middleware(['auth'])
    ->name('admin.modules.toggleAvailability');


// View all teachers
Route::get('/admin/teachers', [TeacherController::class, 'index'])
    ->middleware(['auth'])
    ->name('admin.teachers.index');

// Create teacher
Route::post('/admin/teachers', [TeacherController::class, 'store'])
    ->middleware(['auth'])
    ->name('admin.teachers.store');

// Delete teacher
Route::delete('/admin/teachers/{user}', [TeacherController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('admin.teachers.destroy');
/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
*/

// Teacher dashboard
Route::get('/teacher/dashboard', function () {
    return view('teacher.dashboard');
})->middleware(['auth'])->name('teacher.dashboard');

// Teacher: view assigned modules
Route::get('/teacher/modules', [TeacherModuleController::class, 'index'])
    ->middleware(['auth'])
    ->name('teacher.modules.index');

// Teacher: view students in module
Route::get('/teacher/modules/{module}/students', [TeacherModuleController::class, 'students'])
    ->middleware(['auth'])
    ->name('teacher.modules.students');

// Teacher: set PASS / FAIL result
Route::post('/teacher/modules/{module}/result', [TeacherModuleController::class, 'setResult'])
    ->middleware(['auth'])
    ->name('teacher.modules.result');

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES (REQUIRED BY BREEZE NAVIGATION)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // REQUIRED by Breeze navigation
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile');

    // Optional but good practice
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD FALLBACK (BREEZE DEFAULT)
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
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
