<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $role = $student->role->role;

        // ======================
        // Completed modules (COMMON)
        // ======================
        $completedModules = $student->modules()
            ->wherePivotNotNull('completed_at')
            ->get();

        // ======================
        // OLD STUDENT → LOCKED VIEW
        // ======================
        if ($role === 'old_student') {
            return view('student.dashboard', [
                'completedModules' => $completedModules,
                'enrolledModules'  => collect(),
                'modules'          => collect(),
                'activeCount'      => 0,
                'enrolledModuleIds'=> [],
            ]);
        }

        // ======================
        // ACTIVE STUDENT VIEW
        // ======================
        $enrolledModules = $student->modules()
            ->wherePivotNull('completed_at')
            ->get();

        $activeCount = $enrolledModules->count();

        $enrolledModuleIds = $enrolledModules->pluck('id')->toArray();

        $modules = Module::withCount([
            'users as enrolled_students_count' => function ($q) {
                $q->whereNull('completed_at');
            }
        ])
        ->where('is_active', true)
        ->get();

        return view('student.dashboard', [
            'modules'             => $modules,
            'enrolledModules'     => $enrolledModules,
            'completedModules'    => $completedModules,
            'activeCount'         => $activeCount,
            'enrolledModuleIds'   => $enrolledModuleIds,
        ]);
    }
}
