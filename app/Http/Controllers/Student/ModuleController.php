<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT DASHBOARD
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $student = auth()->user();

        // Active enrolled modules
        $enrolledModules = $student->modules()
        ->wherePivotNull('completed_at')
        ->withCount([
            'students as enrolled_students_count' => function ($q) {
                $q->wherePivotNull('completed_at');
            }
        ])
        ->get();

    // Completed modules
    $completedModules = $student->modules()
        ->wherePivotNotNull('completed_at')
        ->get();

    // ALL available modules (even if student hit max 4)
    $availableModules = Module::where('available', true)
        ->whereDoesntHave('students', function ($q) use ($student) {
            $q->where('users.id', $student->id);
        })
        ->withCount([
            'students as enrolled_students_count' => function ($q) {
                $q->wherePivotNull('completed_at');
            }
        ])
        ->get();

    return view('student.dashboard', compact(
        'enrolledModules',
        'availableModules',
        'completedModules'
    ));
}
    /*
    |--------------------------------------------------------------------------
    | ENROLL STUDENT
    |--------------------------------------------------------------------------
    */
    public function enroll(Module $module)
    {
        $student = Auth::user();

        if ($student->role->role !== 'student') {
            abort(403);
        }

        if (!$module->available) {
            return back()->with('error', 'This module is currently unavailable.');
        }

        try {
            DB::transaction(function () use ($student, $module) {

                if ($student->modules()->where('modules.id', $module->id)->exists()) {
                    throw new \Exception('You are already enrolled in this module.');
                }

                if ($student->activeModules()->count() >= 4) {
                    throw new \Exception('You can only enrol in a maximum of 4 modules.');
                }

                if (
                    $module->students()
                        ->wherePivotNull('completed_at')
                        ->count() >= 10
                ) {
                    throw new \Exception('This module is full.');
                }

                $student->modules()->attach($module->id, [
                    'enrolled_at'  => now(),
                    'status'       => null,
                    'completed_at' => null,
                ]);
            });

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Successfully enrolled in module.');
    }
}
