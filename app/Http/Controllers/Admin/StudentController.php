<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['student', 'old_student']);
        })
        ->with('role')
        ->get();

        // THIS WAS MISSING 👇
        $roles = UserRole::whereIn('name', ['student', 'old_student'])->get();

        return view('admin.students.index', compact('students', 'roles'));
    }

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
}
