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
    | Mass assignment
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
    | Hidden attributes
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
    | TEACHER: Modules they teach
    |--------------------------------------------------------------------------
    */
    public function teachingModules(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'module_teacher',
            'teacher_id',
            'module_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT: Modules enrolled
    |--------------------------------------------------------------------------
    */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'module_user',
            'user_id',
            'module_id'
        )
        ->withPivot([
            'enrolled_at',
            'status',        // NULL | PASS | FAIL
            'completed_at',
        ])
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Active modules (not yet completed)
     */
    public function activeModules()
    {
        return $this->modules()
            ->wherePivotNull('completed_at');
    }

    /**
     * Completed modules (PASS / FAIL)
     */
    public function completedModules()
    {
        return $this->modules()
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
    | AUTO MOVE TO OLD STUDENT
    |--------------------------------------------------------------------------
    */

    /**
     * Promote student to old_student when all modules are completed
     */
    public function checkAndPromoteToOldStudent(): void
    {
        // Only apply to current students
        if ($this->role->role !== 'student') {
            return;
        }

        // No active modules AND has completed modules
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
