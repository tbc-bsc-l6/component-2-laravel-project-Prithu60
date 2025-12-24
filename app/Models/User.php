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
    | STUDENT: Modules they are enrolled in (CORE RELATION)
    |--------------------------------------------------------------------------
    | This is what controllers use: auth()->user()->modules()
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
    |--------------------------------------------------------------------------
    | STUDENT HELPERS (ASSIGNMENT RULES)
    |--------------------------------------------------------------------------
    */

    // Active modules (currently enrolled, not completed)
    public function activeModules()
    {
        return $this->modules()
            ->wherePivot('status', 'ENROLLED');
    }

    // Completed modules (PASS / FAIL history)
    public function completedModules()
    {
        return $this->modules()
            ->wherePivotIn('status', ['PASS', 'FAIL']);
    }

    // Can student enroll? (MAX 4 active modules)
    public function canEnroll(): bool
    {
        return $this->activeModules()->count() < 4;
    }
}
