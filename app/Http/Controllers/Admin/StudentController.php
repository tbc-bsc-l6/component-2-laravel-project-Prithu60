<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * CURRENT students (Student + Old Student mixed list)
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
                    'completed_at'
                ])
            ])
            ->get();

        $roles = UserRole::whereIn('role', ['student', 'old_student'])->get();

        return view('admin.students.index', compact('students', 'roles', 'search'));
    }

    /**
     * UPDATE ROLE (ADMIN)
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
     * OLD STUDENTS ONLY (COMPLETED MODULES VIEW)
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
