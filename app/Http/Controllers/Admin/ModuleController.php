<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST MODULES (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $modules = Module::withCount([
            'teachers',

            // Active students (STUDENT ROLE ONLY)
            'students as active_students_count' => function ($q) {
                $q->whereNull('module_user.completed_at');
            },

            // Completed students (STUDENT ROLE ONLY)
            'students as completed_students_count' => function ($q) {
                $q->whereNotNull('module_user.completed_at');
            },
        ])
        ->orderByDesc('created_at')
        ->get();

        return view('admin.modules.index', compact('modules'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE MODULE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Module::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => true,
        ]);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT MODULE
    |--------------------------------------------------------------------------
    */
    public function edit(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE MODULE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $module->update($validated);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE MODULE
    |--------------------------------------------------------------------------
    */
    public function destroy(Module $module)
    {
        $module->teachers()->detach();
        $module->students()->detach();
        $module->delete();

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE MODULE ACTIVE / ARCHIVED
    |--------------------------------------------------------------------------
    */
    public function toggleStatus(Module $module)
    {
        $module->update([
            'is_active' => ! $module->is_active,
        ]);

        return redirect()
            ->route('admin.modules.index')
            ->with(
                'success',
                $module->is_active
                    ? 'Module unarchived successfully.'
                    : 'Module archived successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW STUDENTS IN MODULE (STUDENTS ONLY)
    |--------------------------------------------------------------------------
    */
    public function students(Module $module)
    {
        $students = $module->students()
            ->with('role')
            ->withPivot([
                'enrolled_at',
                'completed_at',
                'status',
            ])
            ->orderBy('pivot_enrolled_at')
            ->get();

        return view('admin.modules.students', compact('module', 'students'));
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGN TEACHERS
    |--------------------------------------------------------------------------
    */
    public function assignTeachers(Module $module)
    {
        $teachers = User::whereHas('role', function ($query) {
            $query->where('role', 'teacher');
        })->get();

        return view('admin.modules.assign-teachers', compact('module', 'teachers'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE ASSIGNED TEACHERS (FIXED)
    |--------------------------------------------------------------------------
    */
    public function storeTeachers(Request $request, Module $module)
    {
        $validated = $request->validate([
            'teachers'   => ['nullable', 'array'],
            'teachers.*' => ['exists:users,id'],
        ]);

        $syncData = [];

        foreach ($validated['teachers'] ?? [] as $teacherId) {
            $syncData[$teacherId] = [
                'teacher_assigned_at' => now(),
            ];
        }

        $module->teachers()->sync($syncData);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Teachers assigned successfully.');
    }
}
