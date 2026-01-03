<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;

/*
|--------------------------------------------------------------------------
| TEACHER CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;

/*
|--------------------------------------------------------------------------
| STUDENT CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
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
| Auth
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
Route::middleware('auth')->get('/dashboard', function () {
    return match (auth()->user()->role->role) {
        'admin'       => redirect()->route('admin.dashboard'),
        'teacher'     => redirect()->route('teacher.dashboard'),
        'student',
        'old_student' => redirect()->route('student.dashboard'),
        default       => abort(403),
    };
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        | Modules
        */
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

        Route::get('/modules/{module}/students', [AdminModuleController::class, 'students'])
            ->name('modules.students');

        Route::get('/modules/{module}/assign-teachers', [AdminModuleController::class, 'assignTeachers'])
            ->name('modules.assign-teachers');

        Route::post('/modules/{module}/assign-teachers', [AdminModuleController::class, 'storeTeachers'])
            ->name('modules.assign-teachers.store');

        /*
        | Teachers
        */
        Route::get('/teachers', [TeacherController::class, 'index'])
            ->name('teachers.index');

        Route::post('/teachers', [TeacherController::class, 'store'])
            ->name('teachers.store');

        Route::delete('/teachers/{user}', [TeacherController::class, 'destroy'])
            ->name('teachers.destroy');

        /*
        | Students
        */
        Route::get('/students', [AdminStudentController::class, 'index'])
            ->name('students.index');

        Route::patch('/students/{student}/role', [AdminStudentController::class, 'updateRole'])
            ->name('students.updateRole');

        Route::get('/students/{student}/enrolments', [AdminStudentController::class, 'enrolments'])
            ->name('students.enrolments');

        Route::delete(
            '/students/{student}/modules/{module}',
            [AdminStudentController::class, 'removeFromModule']
        )->name('students.removeFromModule');

        Route::get('/old-students', [AdminStudentController::class, 'oldStudents'])
            ->name('old-students.index');
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

        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/modules', [TeacherModuleController::class, 'index'])
            ->name('modules.index');

        Route::get('/modules/{module}', [TeacherModuleController::class, 'show'])
            ->name('modules.show');

        Route::post('/modules/{module}/students/{student}/pass',
            [TeacherModuleController::class, 'pass']
        )->name('modules.students.pass');

        Route::post('/modules/{module}/students/{student}/fail',
            [TeacherModuleController::class, 'fail']
        )->name('modules.students.fail');

        Route::get('/students', [TeacherStudentController::class, 'index'])
            ->name('students.index');

        Route::get('/students/old', [TeacherStudentController::class, 'old'])
            ->name('students.old');
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES (CURRENT + OLD)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student,old_student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->name('dashboard');

        // ✅ Modules list (NEW)
        Route::get('/modules', [StudentModuleController::class, 'index'])
            ->name('modules.index');

        // Enroll (only works for normal students)
        Route::post('/modules/{module}/enroll', [StudentModuleController::class, 'enroll'])
            ->name('modules.enroll');

        // Optional: history page (if you keep it)
        Route::get('/modules/history', [StudentModuleController::class, 'history'])
            ->name('history');
    });

/*
|--------------------------------------------------------------------------
| PROFILE
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
