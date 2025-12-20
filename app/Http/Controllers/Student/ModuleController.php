<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * View completed modules with PASS / FAIL history
     */
    public function history()
    {
        // Ensure only students or old students can access
        if (!Auth::check() || !in_array(Auth::user()->role->name, ['student', 'old_student'])) {
            abort(403);
        }

        // Get completed modules (where completion_date is set)
        $completedModules = Auth::user()
            ->modules()
            ->wherePivotNotNull('completion_date')
            ->get();

        return response()->json($completedModules);
    }
}
