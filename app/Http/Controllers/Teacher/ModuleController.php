<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Display modules assigned to the authenticated teacher
     */
    public function index()
    {
        // Ensure only teachers can access
        if (!Auth::check() || Auth::user()->role->name !== 'teacher') {
            abort(403);
        }

        // Get modules assigned to this teacher
        $modules = Auth::user()->teachingModules()->get();

        // For now, return JSON (UI will come later)
        return response()->json($modules);
    }
}
