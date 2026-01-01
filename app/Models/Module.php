<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Module extends Model
{
    /**
     * Explicit table name
     */
    protected $table = 'modules';

    /**
     * Mass assignment
     */
    protected $guarded = [];

    /**
     * Maximum allowed students
     */
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
            'module_teacher',
            'module_id',
            'teacher_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Students enrolled in this module
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
        ->withPivot([
            'enrolled_at',
            'status',
            'completed_at',
        ])
        ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | ✅ Alias: users() → students()
    | (prevents dashboard crashes)
    |--------------------------------------------------------------------------
    */
    public function users(): BelongsToMany
    {
        return $this->students();
    }

    /*
    |--------------------------------------------------------------------------
    | Active (not completed) students
    |--------------------------------------------------------------------------
    */
    public function activeStudents(): BelongsToMany
    {
        return $this->students()->wherePivotNull('completed_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Count active students
    |--------------------------------------------------------------------------
    */
    public function enrolledStudentsCount(): int
    {
        return $this->activeStudents()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Check if module is full
    |--------------------------------------------------------------------------
    */
    public function isFull(): bool
    {
        return $this->enrolledStudentsCount() >= self::MAX_STUDENTS;
    }

    /*
    |--------------------------------------------------------------------------
    | Check if user is enrolled
    |--------------------------------------------------------------------------
    */
    public function isEnrolledBy(User $user): bool
    {
        return $this->students()
            ->where('users.id', $user->id)
            ->wherePivotNull('completed_at')
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Check if user completed module
    |--------------------------------------------------------------------------
    */
    public function isCompletedBy(User $user): bool
    {
        return $this->students()
            ->where('users.id', $user->id)
            ->wherePivotNotNull('completed_at')
            ->exists();
    }
}
