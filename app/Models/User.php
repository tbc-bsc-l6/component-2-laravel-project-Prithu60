<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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
            'user_id',
            'module_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT: Modules they are enrolled in
    |--------------------------------------------------------------------------
    */
    public function enrolledModules(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            'module_user',
            'user_id',
            'module_id'
        )->withPivot([
            'student_start_date',
            'completion_date',
            'pass_fail',
        ])
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT HELPERS (ASSIGNMENT LOGIC)
    |--------------------------------------------------------------------------
    */

    // Active modules (not completed yet)
    public function activeModules()
    {
        return $this->enrolledModules()
            ->wherePivotNull('completion_date');
    }

    // Completed modules (history)
    public function completedModules()
    {
        return $this->enrolledModules()
            ->whereNotNull('completion_date');
    }

    // Can student enroll? (MAX 4 modules)
    public function canEnroll(): bool
    {
        return $this->activeModules()->count() < 4;
    }
}
