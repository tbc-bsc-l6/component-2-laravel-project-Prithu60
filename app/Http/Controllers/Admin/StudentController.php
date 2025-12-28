<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Show CURRENT students
     */
    public function index()
    {
        $studentRole = UserRole::where('role', 'student')->first();

        $students = User::where('user_role_id', $studentRole->id)
        ->with('role')
        ->get();

    // 👇 THIS IS THE KEY FIX
        $roles = UserRole::whereIn('role', ['student', 'old_student'])->get();

        return view('admin.students.index', compact('students', 'roles'));
    }


    /**
     * Update student role (admin action)
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

    public function oldStudents()
    {
        $oldRole = UserRole::where('role', 'old_student')->first();

        $students = User::where('user_role_id', $oldRole->id)
        ->with('role')
        ->get();

        return view('admin.students.old', compact('students'));
    }
}
