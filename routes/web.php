<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentEnrollmentController;

use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;

use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;

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
| Auth (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (Role Based)
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
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Modules CRUD
        Route::get('/modules', [AdminModuleController::class, 'index'])
            ->name('modules.index');

        Route::post('/modules', [AdminModuleController::class, 'store'])
            ->name('modules.store');

        Route::get('/modules/{module}/edit', [AdminModuleController::class, 'edit'])
            ->name('modules.edit');

        Route::put('/modules/{module}', [AdminModuleController::class, 'update'])
            ->name('modules.update');

        Route::delete('/modules/{module}', [AdminModuleController::class, 'destroy'])
            ->name('modules.destroy');

        // ✅ TOGGLE MODULE ACTIVE / ARCHIVE
        Route::patch('/modules/{module}/toggle', [AdminModuleController::class, 'toggle'])
            ->name('modules.toggle');

        // Assign Teacher to Module
        Route::post('/modules/{module}/assign-teacher', [AdminModuleController::class, 'assignTeacher'])
            ->name('modules.assignTeacher');

        // Teachers
        Route::get('/teachers', [TeacherController::class, 'index'])
            ->name('teachers.index');

        Route::post('/teachers', [TeacherController::class, 'store'])
            ->name('teachers.store');

        Route::delete('/teachers/{user}', [TeacherController::class, 'destroy'])
            ->name('teachers.destroy');

        // Students
        Route::get('/students', [AdminStudentController::class, 'index'])
            ->name('students.index');

        Route::delete('/students/{student}/remove/{module}', [AdminStudentController::class, 'removeFromModule'])
            ->name('students.removeFromModule');
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
| PROFILE (Breeze Requirement)
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
