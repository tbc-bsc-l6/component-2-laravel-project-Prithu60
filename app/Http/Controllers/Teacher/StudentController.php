<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    /*
    |-------------------------------------------------------------------------- 
    | Active students across all modules taught by this teacher
    |-------------------------------------------------------------------------- 
    */
    public function index()
    {
        $teacher = auth()->user();

        $modules = $teacher->teachingModules()
            ->with([
                'students' => function ($query) {
                    $query->wherePivotNull('completed_at')
                          ->withPivot([
                              'enrolled_at',
                              'completed_at',
                              'status',
                          ]);
                }
            ])
            ->get();

        return view('teacher.students.index', compact('modules'));
    }

    /*
    |-------------------------------------------------------------------------- 
    | Completed (Old) students across all modules
    |-------------------------------------------------------------------------- 
    */
    public function old()
    {
        $teacher = auth()->user();

        $modules = $teacher->teachingModules()
            ->with([
                'students' => function ($query) {
                    $query->wherePivotNotNull('completed_at')
                          ->withPivot([
                              'enrolled_at',
                              'completed_at',
                              'status',
                          ]);
                }
            ])
            ->get();

        return view('teacher.students.old', compact('modules'));
    }
}
