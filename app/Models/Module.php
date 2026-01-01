<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Module extends Model
{
    protected $table = 'modules';

    protected $guarded = [];

    public const MAX_STUDENTS = 10;

    /*
    |----------------------------------------------------------------------
    | Teachers
    |----------------------------------------------------------------------
    */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'module_teacher',
            'module_id',
            'teacher_id'
        )->withTimestamps();
    }

    /*
    |----------------------------------------------------------------------
    | Students
    |----------------------------------------------------------------------
    */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'module_user',
            'module_id',
            'user_id'
        )
        ->withPivot([
            'enrolled_at',
            'status',
            'completed_at',
        ])
        ->withTimestamps();
    }

    /*
    |----------------------------------------------------------------------
    | Helpers (VERY IMPORTANT)
    |----------------------------------------------------------------------
    */
    public function enrolledStudentsCount(): int
    {
        return $this->students()
            ->wherePivotNull('completed_at')
            ->count();
    }

    public function isFull(): bool
    {
        return $this->enrolledStudentsCount() >= self::MAX_STUDENTS;
    }

    public function isEnrolledBy(User $user): bool
    {
        return $this->students()
            ->where('users.id', $user->id)
            ->wherePivotNull('completed_at')
            ->exists();
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->students()
            ->where('users.id', $user->id)
            ->wherePivotNotNull('completed_at')
            ->exists();
    }
}
