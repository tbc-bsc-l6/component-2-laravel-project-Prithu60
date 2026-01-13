<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Module;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST STUDENTS (STUDENT + OLD STUDENT)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $students = User::whereHas('role', function ($q) {
                $q->whereIn('role', ['student', 'old_student']);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->with([
                'role',
                'modules' => function ($q) {
                    $q->withPivot([
                        'enrolled_at',
                        'status',
                        'completed_at',
                    ]);
                }
            ])
            ->orderBy('name')
            ->get();

        $roles = UserRole::whereIn('role', ['student', 'old_student'])->get();

        return view('admin.students.index', compact(
            'students',
            'roles',
            'search'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STUDENT ROLE (ADMIN CONTROLLED)
    |--------------------------------------------------------------------------
    */
    public function updateRole(Request $request, User $student)
    {
        $request->validate([
            'role_id' => ['required', 'exists:user_roles,id'],
        ]);

        $student->update([
            'user_role_id' => $request->role_id,
        ]);

        return back()->with('success', 'Student role updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW A SINGLE STUDENT'S ENROLMENTS (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function enrolments(User $student)
    {
        $student->load([
            'role',
            'modules' => function ($q) {
                $q->withPivot([
                    'enrolled_at',
                    'status',
                    'completed_at',
                ])->orderBy('pivot_enrolled_at');
            }
        ]);

        return view('admin.students.enrolments', compact('student'));
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE STUDENT FROM AN ACTIVE MODULE ONLY
    |--------------------------------------------------------------------------
    */
    public function removeFromModule(User $student, Module $module)
    {
        $pivot = $student->modules()
            ->where('modules.id', $module->id)
            ->first()
            ?->pivot;

        if (!$pivot) {
            return back()->with('error', 'Student is not enrolled in this module.');
        }

        // Assignment rule: completed modules are historical and must remain
        if ($pivot->completed_at !== null) {
            return back()->with(
                'error',
                'Completed modules cannot be removed.'
            );
        }

        $student->modules()->detach($module->id);

        return back()->with(
            'success',
            'Student removed from module successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OLD STUDENTS — COMPLETED MODULES ONLY
    |--------------------------------------------------------------------------
    */
    public function oldStudents()
{
    $oldStudentRole = UserRole::where('role', 'old_student')->firstOrFail();

    $students = User::where('user_role_id', $oldStudentRole->id)
        ->with([
            'modules' => function ($q) {
                $q->whereNotNull('module_user.completed_at')
                  ->withPivot([
                      'status',
                      'enrolled_at',
                      'completed_at',
                  ])
                  ->orderBy('pivot_completed_at', 'desc');
            }
        ])
        ->orderBy('name')
        ->get();

    // ✅ ADD THIS (needed by Blade)
    $roles = UserRole::whereIn('role', ['student', 'old_student'])->get();

    return view('admin.students.old', compact('students', 'roles'));
}

}
