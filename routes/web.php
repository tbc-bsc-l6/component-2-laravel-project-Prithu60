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
use App\Http\Controllers\Admin\UserController as AdminUserController;

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
        'student'     => redirect()->route('student.dashboard'),
        'old_student' => redirect()->route('student.modules.completed'),
        default       => abort(403),
    };
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| 🔐 OLD STUDENT SAFETY REDIRECT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:old_student'])
    ->get('/student/dashboard', function () {
        return redirect()->route('student.modules.completed');
    });

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

        // ✅ ARCHIVE / UNARCHIVE (FIXED)
        Route::patch(
            '/modules/{module}/toggle-status',
            [AdminModuleController::class, 'toggleStatus']
        )->name('modules.toggle-status');

        Route::get('/modules/{module}/students', [AdminModuleController::class, 'students'])
            ->name('modules.students');

        Route::get('/modules/{module}/assign-teachers', [AdminModuleController::class, 'assignTeachers'])
            ->name('modules.assign-teachers');

        Route::post('/modules/{module}/assign-teachers', [AdminModuleController::class, 'storeTeachers'])
            ->name('modules.assign-teachers.store');

        Route::get('/teachers', [TeacherController::class, 'index'])
            ->name('teachers.index');

        Route::post('/teachers', [TeacherController::class, 'store'])
            ->name('teachers.store');

        Route::delete('/teachers/{user}', [TeacherController::class, 'destroy'])
            ->name('teachers.destroy');

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

        Route::get(
            '/users/{user}/promote-teacher',
            [AdminUserController::class, 'promoteToTeacher']
        )->name('users.promote-teacher');

        Route::post(
            '/users/{user}/promote-teacher',
            [AdminUserController::class, 'storeTeacher']
        )->name('users.promote-teacher.store');

        Route::patch(
            '/users/{user}/demote-student',
            [AdminUserController::class, 'demoteToStudent']
        )->name('users.demote-student');
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
| STUDENT ROUTES — ACTIVE STUDENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/modules', [StudentModuleController::class, 'index'])
            ->name('modules.index');

        Route::post('/modules/{module}/enroll', [StudentModuleController::class, 'enroll'])
            ->name('modules.enroll');

        Route::get('/modules/history', [StudentModuleController::class, 'history'])
            ->name('history');
    });

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES — OLD STUDENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:old_student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/modules/completed', [StudentModuleController::class, 'completed'])
            ->name('modules.completed');
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
