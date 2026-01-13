<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Module extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const MAX_STUDENTS = 10;

    /*
    |--------------------------------------------------------------------------
    | Teachers assigned to this module
    |--------------------------------------------------------------------------
    */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'module_user',
            'module_id',
            'user_id'
        )
        ->whereNotNull('module_user.teacher_assigned_at')
        ->withPivot('teacher_assigned_at')
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Students enrolled in this module (ONLY student role)
    |--------------------------------------------------------------------------
    */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'module_user',
            'module_id',
            'user_id'
        )
        ->where('users.user_role_id', 3) // 👈 STUDENT ROLE ONLY
        ->withPivot([
            'enrolled_at',
            'completed_at',
            'status',
        ])
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods (used across app)
    |--------------------------------------------------------------------------
    */
    public function activeStudentsCount(): int
    {
        return $this->students()
            ->wherePivotNotNull('enrolled_at')
            ->wherePivotNull('completed_at')
            ->count();
    }

    public function completedStudentsCount(): int
    {
        return $this->students()
            ->wherePivotNotNull('completed_at')
            ->count();
    }

    public function teachersCount(): int
    {
        return $this->teachers()->count();
    }

    public function isFull(): bool
    {
        return $this->activeStudentsCount() >= self::MAX_STUDENTS;
    }
}
