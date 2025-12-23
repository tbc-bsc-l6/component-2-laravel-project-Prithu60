<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::withCount(['teachers', 'students'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.modules.index', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Module::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module created successfully.');
    }

    public function edit(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $module->update($validated);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    public function destroy(Module $module)
    {
        $module->teachers()->detach();
        $module->students()->detach();
        $module->delete();

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module deleted successfully.');
    }

    public function toggle(Module $module)
    {
        $module->update([
            'is_active' => !$module->is_active,
        ]);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module status updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW STUDENTS IN MODULE (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function students(Module $module)
    {
        $students = $module->students()->with('role')->get();

        return view('admin.modules.students', compact('module', 'students'));
    }
}
