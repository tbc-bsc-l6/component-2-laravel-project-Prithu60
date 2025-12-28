<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;

class ModuleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View modules assigned to the authenticated teacher
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $modules = auth()->user()
            ->teachingModules()
            ->get();

        return view('teacher.modules.index', compact('modules'));
    }

    /*
    |--------------------------------------------------------------------------
    | View students enrolled in a specific module
    |--------------------------------------------------------------------------
    */
    public function show(Module $module)
    {
        // Ensure teacher is assigned to this module
        $this->authorizeTeacher($module);

        // Only active students (not yet completed)
        $students = $module->students()
            ->wherePivotNull('completed_at')
            ->get();

        return view('teacher.modules.show', compact('module', 'students'));
    }

    /*
    |--------------------------------------------------------------------------
    | Mark student as PASS
    |--------------------------------------------------------------------------
    */
    public function pass(Module $module, User $student)
    {
        $this->authorizeTeacher($module);

        $module->students()->updateExistingPivot($student->id, [
            'status'       => 'PASS',
            'completed_at' => now(),
        ]);

        // Refresh & auto-promote student if needed
        $student->refresh();
        $student->checkAndPromoteToOldStudent();

        return back()->with('success', 'Student marked as PASS.');
    }

    /*
    |--------------------------------------------------------------------------
    | Mark student as FAIL
    |--------------------------------------------------------------------------
    */
    public function fail(Module $module, User $student)
    {
        $this->authorizeTeacher($module);

        $module->students()->updateExistingPivot($student->id, [
            'status'       => 'FAIL',
            'completed_at' => now(),
        ]);

        // Refresh & auto-promote student if needed
        $student->refresh();
        $student->checkAndPromoteToOldStudent();

        return back()->with('success', 'Student marked as FAIL.');
    }

    /*
    |--------------------------------------------------------------------------
    | Internal authorization helper
    |--------------------------------------------------------------------------
    */
    private function authorizeTeacher(Module $module): void
    {
        abort_unless(
            $module->teachers()
                ->where('users.id', auth()->id())
                ->exists(),
            403
        );
    }
}
