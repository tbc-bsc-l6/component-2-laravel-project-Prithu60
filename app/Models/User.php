<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// ✅ REQUIRED IMPORTS
use App\Models\UserRole;
use App\Models\Module;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ USE PROPERTY, NOT METHOD
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |------------------------------------------------------------------
    | ROLE
    |------------------------------------------------------------------
    */
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    /*
    |------------------------------------------------------------------
    | TEACHER: Modules they teach
    |------------------------------------------------------------------
    */
    public function teachingModules(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'module_teacher',
            'teacher_id',   // user_id of teacher
            'module_id'
        )->withTimestamps();
    }

    /*
    |------------------------------------------------------------------
    | STUDENT: Modules enrolled
    |------------------------------------------------------------------
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
            'status',        // ENROLLED | PASS | FAIL
            'completed_at',
        ])
        ->withTimestamps();
    }

    /*
    |------------------------------------------------------------------
    | STUDENT HELPERS
    |------------------------------------------------------------------
    */

    public function activeModules()
    {
        return $this->modules()
            ->wherePivot('status', 'ENROLLED');
    }

    public function completedModules()
    {
        return $this->modules()
            ->wherePivotIn('status', ['PASS', 'FAIL']);
    }

    public function canEnroll(): bool
    {
        return $this->activeModules()->count() < 4;
    }
}
