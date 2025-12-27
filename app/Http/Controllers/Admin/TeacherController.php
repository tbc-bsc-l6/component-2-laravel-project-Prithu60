<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::whereHas('role', function ($q) {
            $q->where('role', 'teacher');
        })->with('teachingModules')->get();

        $modules = Module::where('is_active', true)->get();

        return view('admin.teachers.index', compact('teachers', 'modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:6'],
            'modules'    => ['nullable', 'array'],
            'modules.*'  => ['exists:modules,id'],
        ]);

        $teacherRole = UserRole::where('role', 'teacher')->firstOrFail();

        $teacher = User::create([
            'name'         => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'user_role_id' => $teacherRole->id,
        ]);

        if (!empty($validated['modules'])) {
            $teacher->teachingModules()->sync($validated['modules']);
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher created and modules assigned successfully.');
    }

    public function destroy(User $user)
    {
        $user->teachingModules()->detach();
        $user->delete();

        return back()->with('success', 'Teacher deleted successfully.');
    }
}
