<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * View modules assigned to the authenticated teacher
     */
    public function index()
    {
        if (!Auth::check() || Auth::user()->role->name !== 'teacher') {
            abort(403);
        }

        return response()->json(
            Auth::user()->teachingModules()->get()
        );
    }

    /**
     * View students enrolled in a specific module
     */
    public function students(int $moduleId)
    {
        if (!Auth::check() || Auth::user()->role->name !== 'teacher') {
            abort(403);
        }

        $module = Auth::user()
            ->teachingModules()
            ->where('modules.id', $moduleId)
            ->firstOrFail();

        return response()->json([
            'module' => $module->name,
            'students' => $module->students()->get()
        ]);
    }

    /**
     * Set PASS / FAIL for a student in a module
     */
    public function setResult(Request $request, int $moduleId)
    {
        if (!Auth::check() || Auth::user()->role->name !== 'teacher') {
            abort(403);
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'result' => 'required|in:PASS,FAIL',
        ]);

        $module = Auth::user()
            ->teachingModules()
            ->where('modules.id', $moduleId)
            ->firstOrFail();

        $module->students()->updateExistingPivot(
            $validated['student_id'],
            [
                'pass_fail' => $validated['result'],
                'completion_date' => now(),
            ]
        );

        return back()->with('success', 'Result recorded successfully.');
    }
}
