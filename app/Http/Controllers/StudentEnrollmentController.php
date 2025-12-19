<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
    /**
     * Enrol the authenticated student into a module
     */
    public function enroll(Module $module)
    {
        $student = Auth::user();

        // Safety: only students can enrol
        if ($student->role->name !== 'student') {
            abort(403);
        }

        // Count active enrolments (not completed)
        $activeEnrollments = $student->modules()
            ->wherePivotNull('completion_date')
            ->count();

        if ($activeEnrollments >= 4) {
            return back()->withErrors('You can only enrol in a maximum of 4 modules.');
        }

        // Count active students in this module
        $activeStudentsInModule = $module->students()
            ->wherePivotNull('completion_date')
            ->count();

        if ($activeStudentsInModule >= 10) {
            return back()->withErrors('This module has reached its maximum capacity.');
        }

        // Prevent duplicate enrolment
        if ($student->modules()->where('modules.id', $module->id)->exists()) {
            return back()->withErrors('You are already enrolled in this module.');
        }

        // Enrol student
        $student->modules()->attach($module->id, [
            'student_start_date' => now(),
        ]);

        return back()->with('success', 'Successfully enrolled in module.');
    }
}
