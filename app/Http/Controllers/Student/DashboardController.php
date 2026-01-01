<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        // Active enrolled modules
        $enrolledModules = $student->modules()
            ->wherePivotNull('completed_at')
            ->get();

        // Completed modules
        $completedModules = $student->modules()
            ->wherePivotNotNull('completed_at')
            ->get();

        // ALL modules with enrolled count
        $modules = Module::withCount([
            'users as enrolled_students_count' => function ($q) {
                $q->whereNull('completed_at');
            }
        ])->get();

        return view('student.dashboard', [
            'modules' => $modules,
            'enrolledModules' => $enrolledModules,
            'completedModules' => $completedModules,
        ]);
    }
}
