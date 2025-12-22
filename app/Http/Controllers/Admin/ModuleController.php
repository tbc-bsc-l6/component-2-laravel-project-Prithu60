<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Show all modules (Admin → Modules)
     */
    public function index()
    {
        $modules = Module::with('teachers')->get();

        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Store a newly created module
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Module::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'available' => true,
        ]);

        return back()->with('success', 'Module created successfully.');
    }

    /**
     * Assign teacher to module
     */
    public function assignTeacher(Request $request, Module $module)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        $module->teachers()->syncWithoutDetaching([
            $request->teacher_id,
        ]);

        return back()->with('success', 'Teacher assigned successfully.');
    }

    /**
     * Toggle availability
     */
    public function toggleAvailability(Module $module)
    {
        $module->update([
            'available' => !$module->available,
        ]);

        return back()->with(
            'success',
            $module->available
                ? 'Module is now available'
                : 'Module has been archived'
        );
    }
}
