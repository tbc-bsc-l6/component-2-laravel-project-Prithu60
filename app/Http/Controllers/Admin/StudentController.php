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
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
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

        // ✅ NOW INCLUDES TEACHER
        $roles = UserRole::whereIn('role', [
            'student',
            'old_student',
            'teacher'
        ])->get();

        return view('admin.students.index', compact(
            'students',
            'roles',
            'search'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER ROLE (ADMIN CONTROLLED)
    | Student <-> Old Student <-> Teacher
    |--------------------------------------------------------------------------
    */
    public function updateRole(Request $request, User $student)
    {
        $request->validate([
            'role_id' => ['required', 'exists:user_roles,id'],
        ]);

        $newRole = UserRole::findOrFail($request->role_id);

        // 🚫 Safety: admin cannot demote another admin
        if ($student->role->role === 'admin') {
            return back()->with('error', 'Admin role cannot be changed.');
        }

        // ✅ Update role
        $student->update([
            'user_role_id' => $newRole->id,
        ]);

        return back()->with(
            'success',
            "User role updated to " . ucfirst(str_replace('_', ' ', $newRole->role)) . "."
        );
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

        // Completed modules must remain (assignment rule)
        if ($pivot->completed_at !== null) {
            return back()->with('error', 'Completed modules cannot be removed.');
        }

        $student->modules()->detach($module->id);

        return back()->with('success', 'Student removed from module successfully.');
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

        // ✅ INCLUDE TEACHER HERE TOO
        $roles = UserRole::whereIn('role', [
            'student',
            'old_student',
            'teacher'
        ])->get();

        return view('admin.students.old', compact('students', 'roles'));
    }
}
