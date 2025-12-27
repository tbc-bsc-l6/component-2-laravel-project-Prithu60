<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;

class ModuleController extends Controller
{
    /**
     * View modules assigned to the authenticated teacher
     */
    public function index()
    {
        $modules = auth()->user()->teachingModules()->get();

        return view('teacher.modules.index', compact('modules'));
    }

    /**
     * View students enrolled in a specific module
     */
    public function show(Module $module)
    {
        // Ensure teacher is assigned to this module
        abort_unless(
            $module->teachers()->where('users.id', auth()->id())->exists(),
            403
        );

        $students = $module->students()
            ->wherePivot('status', 'ENROLLED')
            ->get();

        return view('teacher.modules.show', compact('module', 'students'));
    }

    /**
     * Mark student as PASS
     */
    public function pass(Module $module, User $student)
    {
        $module->students()->updateExistingPivot($student->id, [
            'status'       => 'PASS',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Student marked as PASS');
    }

    /**
     * Mark student as FAIL
     */
    public function fail(Module $module, User $student)
    {
        $module->students()->updateExistingPivot($student->id, [
            'status'       => 'FAIL',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Student marked as FAIL');
    }
}
