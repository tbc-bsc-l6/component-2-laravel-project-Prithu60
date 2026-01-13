<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Module;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW MODULE SELECTION PAGE (Student / Old Student → Teacher)
    |--------------------------------------------------------------------------
    */
    public function promoteToTeacher(User $user)
    {
        $currentRole = optional($user->role)->role;

        // Only students or old students can be promoted
        if (! in_array($currentRole, ['student', 'old_student'], true)) {
            abort(403);
        }

        $modules = Module::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.users.promote-teacher', compact('user', 'modules'));
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE: PROMOTE TO TEACHER + ASSIGN MODULES
    |--------------------------------------------------------------------------
    */
    public function storeTeacher(Request $request, User $user)
    {
        $currentRole = optional($user->role)->role;

        // Safety check
        if (! in_array($currentRole, ['student', 'old_student'], true)) {
            abort(403);
        }

        $request->validate([
            'modules'   => ['required', 'array', 'min:1'],
            'modules.*' => ['exists:modules,id'],
        ]);

        $teacherRole = UserRole::where('role', 'teacher')->firstOrFail();

        /*
         | 1) Change role to teacher
         */
        $user->update([
            'user_role_id' => $teacherRole->id,
        ]);

        /*
         | 2) Assign modules as teacher
         */
        $syncData = [];
        foreach ($request->modules as $moduleId) {
            $syncData[$moduleId] = [
                'teacher_assigned_at' => now(),
            ];
        }

        $user->teachingModules()->sync($syncData);

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'User promoted to Teacher successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DEMOTE: TEACHER → STUDENT
    |--------------------------------------------------------------------------
    */
    public function demoteToStudent(User $user)
    {
        // Only teachers can be demoted
        if (optional($user->role)->role !== 'teacher') {
            abort(403);
        }

        $studentRole = UserRole::where('role', 'student')->firstOrFail();

        /*
         | 1) Remove teacher assignments safely
         */
        $user->teachingModules()->detach();

        /*
         | 2) Change role back to student
         */
        $user->update([
            'user_role_id' => $studentRole->id,
        ]);

        return back()->with(
            'success',
            'Teacher successfully changed back to Student.'
        );
    }
}
