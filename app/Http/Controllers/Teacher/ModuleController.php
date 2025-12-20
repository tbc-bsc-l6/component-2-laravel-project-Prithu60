<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role->name !== 'teacher') {
            abort(403);
        }

        return response()->json(
            Auth::user()->teachingModules()->get()
        );
    }

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
}
