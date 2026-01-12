<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\UserRole;
use App\Models\Module;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Attributes
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    /*
    |--------------------------------------------------------------------------
    | =======================
    | TEACHER RELATIONSHIPS
    | =======================
    |--------------------------------------------------------------------------
    */

    /**
     * Active modules taught by teacher
     * (Archived modules are hidden automatically)
     */
    public function teachingModules(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'module_user',
            'user_id',
            'module_id'
        )
            ->whereNotNull('module_user.teacher_assigned_at')
            ->where('modules.is_active', true)
            ->withPivot('teacher_assigned_at')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | =======================
    | STUDENT RELATIONSHIPS
    | =======================
    |--------------------------------------------------------------------------
    */

    /**
     * All enrolled modules (including archived)
     * ⚠ Used only for internal checks / history
     */
    public function allModules(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'module_user',
            'user_id',
            'module_id'
        )
        ->withPivot([
            'enrolled_at',
            'completed_at',
            'status', // PASS | FAIL | NULL
        ])
        ->withTimestamps();
    }

    /**
     * Active & visible modules for student
     * (Archived modules are hidden from UI)
     */
    public function modules(): BelongsToMany
    {
        return $this->allModules()
            ->where('modules.is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Active modules (not completed, not archived)
     */
    public function activeModules()
    {
        return $this->modules()
            ->wherePivotNull('completed_at');
    }

    /**
     * Completed modules (PASS / FAIL)
     * Includes archived modules for correctness
     */
    public function completedModules()
    {
        return $this->allModules()
            ->wherePivotIn('status', ['PASS', 'FAIL']);
    }

    /**
     * Can student enrol in more modules?
     */
    public function canEnroll(): bool
    {
        return $this->activeModules()->count() < 4;
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK HELPERS
    |--------------------------------------------------------------------------
    */

    public function isStudent(): bool
    {
        return optional($this->role)->role === 'student';
    }

    public function isTeacher(): bool
    {
        return optional($this->role)->role === 'teacher';
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO PROMOTION LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Promote student to old_student
     * when all modules are completed
     */
    public function checkAndPromoteToOldStudent(): void
    {
        if (! $this->isStudent()) {
            return;
        }

        if (
            $this->activeModules()->count() === 0 &&
            $this->completedModules()->count() > 0
        ) {
            $oldStudentRole = UserRole::where('role', 'old_student')->first();

            if ($oldStudentRole) {
                $this->update([
                    'user_role_id' => $oldStudentRole->id,
                ]);
            }
        }
    }
}
