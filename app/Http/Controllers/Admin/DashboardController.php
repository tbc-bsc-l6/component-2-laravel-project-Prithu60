<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Module;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::whereHas('role', fn ($q) =>
            $q->whereIn('role', ['student', 'old_student'])
        )->count();

        $currentStudents = User::whereHas('role', fn ($q) =>
            $q->where('role', 'student')
        )->count();

        $oldStudents = User::whereHas('role', fn ($q) =>
            $q->where('role', 'old_student')
        )->count();

        $totalTeachers = User::whereHas('role', fn ($q) =>
            $q->where('role', 'teacher')
        )->count();

        $totalModules = Module::count();

        return view('admin.dashboard', compact(
            'totalStudents',
            'currentStudents',
            'oldStudents',
            'totalTeachers',
            'totalModules'
        ));
    }
}
