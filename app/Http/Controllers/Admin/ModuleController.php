<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Store a newly created module
     */
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role->name !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Module::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'available' => true,
        ]);

        return back()->with('success', 'Module created successfully.');
    }

    /**
     * Assign a teacher to a module
     */
    public function assignTeacher(Request $request, Module $module)
    {
        if (!Auth::check() || Auth::user()->role->name !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        $module->teachers()->syncWithoutDetaching([
            $validated['teacher_id']
        ]);

        return back()->with('success', 'Teacher assigned to module successfully.');
    }
}
