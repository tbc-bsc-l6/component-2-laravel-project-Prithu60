<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentEnrollmentController extends Controller
{
    /**
     * Enrol the authenticated student into a module
     */
    public function enroll(Module $module)
    {
        $student = Auth::user();

        // Safety: only current students can enrol
        if ($student->role->name !== 'student') {
            abort(403);
        }

        // Prevent enrolment if module is archived
        if (!$module->available) {
            return back()->withErrors('This module is currently unavailable for enrolment.');
        }

        try {
            DB::transaction(function () use ($student, $module) {

                // Prevent duplicate enrolment
                if ($student->modules()
                    ->where('modules.id', $module->id)
                    ->exists()) {
                    throw new \Exception('You are already enrolled in this module.');
                }

                // Max 4 active modules per student
                $activeEnrollments = $student->modules()
                    ->wherePivotNull('completion_date')
                    ->count();

                if ($activeEnrollments >= 4) {
                    throw new \Exception('You can only enrol in a maximum of 4 modules.');
                }

                // Max 10 active students per module
                $activeStudentsInModule = $module->students()
                    ->wherePivotNull('completion_date')
                    ->count();

                if ($activeStudentsInModule >= 10) {
                    throw new \Exception('This module has reached its maximum capacity.');
                }

                // Enrol student
                $student->modules()->attach($module->id, [
                    'student_start_date' => now(),
                    'pass_fail' => null,
                    'completion_date' => null,
                ]);
            });

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return back()->with('success', 'Successfully enrolled in module.');
    }
}
