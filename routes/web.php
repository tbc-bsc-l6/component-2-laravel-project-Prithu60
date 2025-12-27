<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
| Auth (Breeze / Livewire)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect (ROLE BASED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role->role;

    return match ($role) {
        'admin'       => redirect()->route('admin.dashboard'),
        'teacher'     => redirect()->route('teacher.dashboard'),
        'student'     => redirect()->route('student.dashboard'),
        'old_student' => redirect()->route('student.history'),
        default       => abort(403),
    };
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', fn () => view('admin.dashboard'))
            ->name('dashboard');

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

        Route::patch('/modules/{module}/toggle', [AdminModuleController::class, 'toggle'])
            ->name('modules.toggle');

        // View students in module
        Route::get('/modules/{module}/students', [AdminModuleController::class, 'students'])
            ->name('modules.students');

        // ✅ Assign teachers to module (FIXED)
        Route::get(
            '/modules/{module}/assign-teachers',
            [AdminModuleController::class, 'assignTeachers']
        )->name('modules.assign-teachers');

        Route::post(
            '/modules/{module}/assign-teachers',
            [AdminModuleController::class, 'storeTeachers']
        )->name('modules.assign-teachers.store');

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

        Route::delete(
            '/students/{student}/remove/{module}',
            [AdminStudentController::class, 'removeFromModule']
        )->name('students.removeFromModule');

        Route::patch(
            '/students/{student}/role',
            [AdminStudentController::class, 'updateRole']
        )->name('students.updateRole');
    });

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        Route::get('/dashboard', fn () => view('teacher.dashboard'))
            ->name('dashboard');

        Route::get('/modules', [TeacherModuleController::class, 'index'])
            ->name('modules.index');

        // View students in module (show page)
        Route::get(
            '/modules/{module}',
            [TeacherModuleController::class, 'show']
        )->name('modules.show');

        // PASS / FAIL
        Route::post(
            '/modules/{module}/students/{student}/pass',
            [TeacherModuleController::class, 'pass']
        )->name('modules.students.pass');

        Route::post(
            '/modules/{module}/students/{student}/fail',
            [TeacherModuleController::class, 'fail']
        )->name('modules.students.fail');
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES (CURRENT STUDENT)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [StudentModuleController::class, 'dashboard'])
            ->name('dashboard');

        Route::post(
            '/modules/{module}/enroll',
            [StudentModuleController::class, 'enroll']
        )->name('modules.enroll');
    });

/*
|--------------------------------------------------------------------------
| OLD STUDENT ROUTES (READ ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:old_student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get(
            '/modules/history',
            [StudentModuleController::class, 'history']
        )->name('history');
    });

/*
|--------------------------------------------------------------------------
| PROFILE (Breeze)
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
