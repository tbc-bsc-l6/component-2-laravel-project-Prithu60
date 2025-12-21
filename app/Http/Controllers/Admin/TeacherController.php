<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    // List all teachers
    public function index()
    {
        $teachers = User::whereHas('role', function ($q) {
            $q->where('name', 'teacher');
        })->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    // Create new teacher
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $teacherRole = UserRole::where('name', 'teacher')->first();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role_id' => $teacherRole->id,
        ]);

        return redirect()->back()->with('success', 'Teacher created successfully');
    }

    // Delete teacher
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Teacher removed');
    }
}
