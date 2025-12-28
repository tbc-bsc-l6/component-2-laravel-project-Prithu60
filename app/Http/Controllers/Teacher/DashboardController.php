<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the teacher dashboard
     */
    public function index()
    {
        return view('teacher.dashboard');
    }
}
