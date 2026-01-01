<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Student dashboard – show all modules
     */
    public function dashboard()
    {
        $student = auth()->user();

        $modules = Module::orderBy('name')->get();

        $enrolledModules = $student->modules()
            ->wherePivotNull('completed_at')
            ->get();

        $completedModules = $student->modules()
            ->wherePivotNotNull('completed_at')
            ->get();

        return view('student.dashboard', compact(
            'modules',
            'enrolledModules',
            'completedModules'
        ));
    }

    /**
     * Enroll student in a module
     */
    public function enroll(Module $module)
    {
        $student = auth()->user();

        // ❌ already enrolled
        if ($student->modules()->where('modules.id', $module->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this module.');
        }

        // ❌ enrollment closed
        if (!$module->available) {
            return back()->with('error', 'Enrollment for this module is currently closed.');
        }

        // ❌ module full
        if ($module->isFull()) {
            return back()->with('error', 'This module is already full.');
        }

        // ❌ max modules reached
        $activeCount = $student->modules()
            ->wherePivotNull('completed_at')
            ->count();

        if ($activeCount >= 4) {
            return back()->with('error', 'You can enroll in a maximum of 4 modules.');
        }

        // ✅ enroll
        $student->modules()->attach($module->id, [
            'enrolled_at' => now(),
        ]);

        return back()->with('success', 'Successfully enrolled in ' . $module->name);
    }
}
