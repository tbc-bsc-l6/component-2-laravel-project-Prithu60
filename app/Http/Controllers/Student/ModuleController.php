<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT DASHBOARD (CURRENT STUDENT ONLY)
    |--------------------------------------------------------------------------
    | Shows:
    | - Enrolled modules (ENROLLED)
    | - Available modules (if space + active)
    | - Completed modules (PASS / FAIL)
    */
    public function dashboard()
    {
        $student = auth()->user();

        // 1️⃣ Currently enrolled modules
        $enrolledModules = $student->modules()
            ->wherePivot('status', 'ENROLLED')
            ->get();

        // 2️⃣ Completed modules
        $completedModules = $student->modules()
            ->wherePivotIn('status', ['PASS', 'FAIL'])
            ->orderByPivot('completed_at', 'desc')
            ->get();

        // 3️⃣ Available modules
        $availableModules = Module::where('is_active', true)
            ->whereDoesntHave('students', function ($query) use ($student) {
                $query->where('users.id', $student->id);
            })
            ->get()
            ->filter(function ($module) {
                return !$module->isFull();
            });

        return view('student.dashboard', compact(
            'enrolledModules',
            'availableModules',
            'completedModules'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ENROLL IN MODULE (CURRENT STUDENT ONLY)
    |--------------------------------------------------------------------------
    */
    public function enroll(Module $module)
    {
        $student = auth()->user();

        // Max 4 active modules
        $activeCount = $student->modules()
            ->wherePivot('status', 'ENROLLED')
            ->count();

        if ($activeCount >= 4) {
            return back()->with('error', 'You can only enroll in a maximum of 4 modules.');
        }

        // Module availability
        if (!$module->is_active) {
            return back()->with('error', 'This module is not available.');
        }

        // Module capacity
        if ($module->isFull()) {
            return back()->with('error', 'This module is already full.');
        }

        // Prevent duplicate enrollment
        if ($student->modules()->where('modules.id', $module->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this module.');
        }

        // Enroll student
        $student->modules()->attach($module->id, [
            'enrolled_at' => now(),
            'status' => 'ENROLLED',
        ]);

        return back()->with('success', 'Successfully enrolled in module.');
    }

    /*
    |--------------------------------------------------------------------------
    | OLD STUDENT HISTORY (READ ONLY)
    |--------------------------------------------------------------------------
    | Only shows completed modules (PASS / FAIL)
    */
    public function history()
    {
        $student = auth()->user();

        $completedModules = $student->modules()
            ->wherePivotIn('status', ['PASS', 'FAIL'])
            ->orderByPivot('completed_at', 'desc')
            ->get();

        return view('student.history', compact('completedModules'));
    }
}
