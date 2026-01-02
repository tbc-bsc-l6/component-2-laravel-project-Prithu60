<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Module;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * LIST STUDENTS (Student + Old Student)
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $students = User::whereHas('role', fn ($q) =>
                $q->whereIn('role', ['student', 'old_student'])
            )
            ->when($search, fn ($q) =>
                $q->where('name', 'like', "%{$search}%")
            )
            ->with([
                'role',
                'modules' => fn ($q) => $q->withPivot([
                    'status',
                    'enrolled_at',
                    'completed_at',
                ])
            ])
            ->get();

        $roles = UserRole::whereIn('role', ['student', 'old_student'])->get();

        return view('admin.students.index', compact('students', 'roles', 'search'));
    }

    /**
     * UPDATE STUDENT ROLE
     */
    public function updateRole(Request $request, User $student)
    {
        $request->validate([
            'role_id' => 'required|exists:user_roles,id',
        ]);

        $student->update([
            'user_role_id' => $request->role_id,
        ]);

        return back()->with('success', 'Student role updated successfully.');
    }

    /**
     * VIEW A STUDENT'S ENROLMENTS (ADMIN)
     */
    public function enrolments(User $student)
    {
        $student->load([
            'modules' => fn ($q) => $q->withPivot([
                'enrolled_at',
                'status',
                'completed_at',
            ])
        ]);

        return view('admin.students.enrolments', compact('student'));
    }

    /**
     * REMOVE STUDENT FROM AN ACTIVE MODULE
     */
    public function removeFromModule(User $student, Module $module)
    {
        // Prevent removal if module already completed
        $pivot = $student->modules()
            ->where('modules.id', $module->id)
            ->first()?->pivot;

        if (!$pivot) {
            return back()->with('error', 'Student is not enrolled in this module.');
        }

        if ($pivot->completed_at !== null) {
            return back()->with('error', 'Completed modules cannot be removed.');
        }

        $student->modules()->detach($module->id);

        return back()->with('success', 'Student removed from module successfully.');
    }

    /**
     * OLD STUDENTS PAGE (COMPLETED MODULES ONLY)
     */
    public function oldStudents()
    {
        $oldRole = UserRole::where('role', 'old_student')->firstOrFail();

        $students = User::where('user_role_id', $oldRole->id)
            ->with([
                'modules' => fn ($q) =>
                    $q->whereNotNull('module_user.completed_at')
                      ->withPivot(['status', 'completed_at'])
            ])
            ->get();

        return view('admin.students.old', compact('students'));
    }
}
