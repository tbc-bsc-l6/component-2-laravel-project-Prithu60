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

        // ==============================
        // Active enrolled modules
        // ==============================
        $enrolledModules = $student->modules()
            ->wherePivotNull('completed_at')
            ->get();

        // Count active modules (for MAX 4 rule)
        $activeCount = $enrolledModules->count();

        // IDs of enrolled modules (for ENROLLED badge)
        $enrolledModuleIds = $enrolledModules->pluck('id')->toArray();

        // ==============================
        // Completed modules
        // ==============================
        $completedModules = $student->modules()
            ->wherePivotNotNull('completed_at')
            ->get();

        // ==============================
        // All modules + enrolled students count
        // ==============================
        $modules = Module::withCount([
            'users as enrolled_students_count' => function ($q) {
                $q->whereNull('completed_at');
            }
        ])->get();

        // ==============================
        // Return view
        // ==============================
        return view('student.dashboard', [
            'modules'             => $modules,
            'enrolledModules'     => $enrolledModules,
            'completedModules'    => $completedModules,
            'activeCount'         => $activeCount,
            'enrolledModuleIds'   => $enrolledModuleIds,
        ]);
    }
}
