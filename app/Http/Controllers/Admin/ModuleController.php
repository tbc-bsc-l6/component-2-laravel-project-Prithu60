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
    | LIST MODULES
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $modules = Module::withCount(['teachers', 'students'])
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
        // Detach relationships first (safe cleanup)
        $module->teachers()->detach();
        $module->students()->detach();

        $module->delete();

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE MODULE ACTIVE / INACTIVE
    |--------------------------------------------------------------------------
    */
    public function toggle(Module $module)
    {
        $module->update([
            'is_active' => ! $module->is_active,
        ]);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module status updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW STUDENTS IN MODULE (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function students(Module $module)
    {
        $students = $module->students()
            ->with('role')
            ->get();

        return view('admin.modules.students', compact('module', 'students'));
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGN TEACHERS TO MODULE (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function assignTeachers(Module $module)
    {
        $teachers = User::whereHas('role', function ($query) {
            $query->where('role', 'teacher');
        })->get();

        return view(
            'admin.modules.assign-teachers',
            compact('module', 'teachers')
        );
    }

    public function storeTeachers(Request $request, Module $module)
    {
        $validated = $request->validate([
            'teachers'   => ['nullable', 'array'],
            'teachers.*' => ['exists:users,id'],
        ]);

        // Sync teachers (attach + detach cleanly)
        $module->teachers()->sync($validated['teachers'] ?? []);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Teachers assigned successfully.');
    }
}
