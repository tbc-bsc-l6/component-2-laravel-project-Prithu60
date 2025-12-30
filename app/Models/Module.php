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
     * Disable mass assignment protection
     * (acceptable for controlled assignment scope)
     */
    protected $guarded = [];

    /*
    |----------------------------------------------------------------------
    | Teachers assigned to this module
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
    | Students enrolled in this module
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
    | Count currently enrolled (active) students
    |----------------------------------------------------------------------
    */
    public function enrolledStudentsCount(): int
    {
        return $this->students()
            ->wherePivotNull('completed_at')
            ->count();
    }

    /*
    |----------------------------------------------------------------------
    | Check if module has reached maximum capacity (10 students)
    |----------------------------------------------------------------------
    */
    public function isFull(): bool
    {
        return $this->enrolledStudentsCount() >= 10;
    }
}
