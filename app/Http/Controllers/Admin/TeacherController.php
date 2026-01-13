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
    /*
    |--------------------------------------------------------------------------
    | LIST TEACHERS (WITH SEARCH)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search = $request->query('q');

        $teachers = User::whereHas('role', function ($q) {
                $q->where('role', 'teacher');
            })
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->with(['teachingModules' => function ($q) {
                $q->where('modules.is_active', true);
            }])
            ->orderBy('name')
            ->get();

        $modules = Module::where('is_active', true)->orderBy('name')->get();

        return view('admin.teachers.index', compact('teachers', 'modules', 'search'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE TEACHER
    |--------------------------------------------------------------------------
    */
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
            $syncData = [];
            foreach ($validated['modules'] as $moduleId) {
                $syncData[$moduleId] = [
                    'teacher_assigned_at' => now(),
                ];
            }
            $teacher->teachingModules()->sync($syncData);
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DEMOTE TEACHER → STUDENT  ✅ NEW
    |--------------------------------------------------------------------------
    */
    public function demoteToStudent(User $user)
    {
        // Safety check
        if ($user->role->role !== 'teacher') {
            return back()->with('error', 'User is not a teacher.');
        }

        // Remove teaching assignments
        $user->teachingModules()->detach();

        // Assign student role
        $studentRole = UserRole::where('role', 'student')->firstOrFail();
        $user->update([
            'user_role_id' => $studentRole->id,
        ]);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher has been changed to Student.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TEACHER
    |--------------------------------------------------------------------------
    */
    public function destroy(User $user)
    {
        $user->teachingModules()->detach();
        $user->delete();

        return back()->with('success', 'Teacher deleted successfully.');
    }
}
