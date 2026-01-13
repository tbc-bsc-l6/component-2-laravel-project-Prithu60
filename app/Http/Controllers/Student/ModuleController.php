<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Show all modules for ACTIVE students (enroll page)
     */
    public function index()
    {
        $student = auth()->user();

        $enrolledModules = $student->modules()
            ->wherePivotNull('completed_at')
            ->pluck('modules.id')
            ->toArray();

        $completedModules = $student->modules()
            ->wherePivotNotNull('completed_at')
            ->pluck('modules.id')
            ->toArray();

        $activeCount = count($enrolledModules);

        $modules = Module::withCount('students')
            ->orderBy('name')
            ->get();

        return view('student.modules.index', compact(
            'modules',
            'enrolledModules',
            'completedModules',
            'activeCount'
        ));
    }

    /**
     * Enroll student in a module (ACTIVE students only)
     */
    public function enroll(Module $module)
    {
        $student = auth()->user();

        // ❌ Old students cannot enroll
        if ($student->role->role === 'old_student') {
            abort(403);
        }

        // Already enrolled
        if ($student->modules()->where('modules.id', $module->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this module.');
        }

        // Module inactive
        if (! $module->is_active) {
            return back()->with('error', 'Enrollment for this module is closed.');
        }

        // Module full
        if ($module->students()->count() >= Module::MAX_STUDENTS) {
            return back()->with('error', 'This module is already full.');
        }

        // Max 4 active modules
        $activeCount = $student->modules()
            ->wherePivotNull('completed_at')
            ->count();

        if ($activeCount >= 4) {
            return back()->with('error', 'You can enroll in a maximum of 4 active modules.');
        }

        $student->modules()->attach($module->id, [
            'enrolled_at' => now(),
        ]);

        return back()->with('success', 'Successfully enrolled in ' . $module->name);
    }

    /**
     * ✅ COMPLETED MODULES (OLD STUDENTS ONLY)
     */
    public function completed()
    {
        $student = auth()->user();

        // Safety check
        if ($student->role->role !== 'old_student') {
            abort(403);
        }

        $completedModules = $student->modules()
            ->wherePivotNotNull('completed_at')
            ->get();

        return view('student.modules.completed', compact('completedModules'));
    }
}
