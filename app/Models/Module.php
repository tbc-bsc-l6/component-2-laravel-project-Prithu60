<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Module extends Model
{
    protected $guarded = [];

    public const MAX_STUDENTS = 10;

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

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'module_user',
            'module_id',
            'user_id'
        )
        ->whereNotNull('module_user.enrolled_at')
        ->withPivot([
            'enrolled_at',
            'completed_at',
            'status',
        ])
        ->withTimestamps();
    }

    public function activeStudentsCount(): int
    {
        return $this->students()
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
