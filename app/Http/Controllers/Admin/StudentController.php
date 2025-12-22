<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display all students
     */
    public function index()
    {
        // Admin only
        if (!Auth::check() || Auth::user()->role->name !== 'admin') {
            abort(403);
        }

        // Get current + old students
        $students = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['student', 'old_student']);
        })->get();

        return view('admin.students.index', compact('students'));
    }
}
