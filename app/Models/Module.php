<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    /**
     * Explicit table name (avoid guessing)
     */
    protected $table = 'modules';

    /**
     * Disable mass-assignment protection entirely
     * (SAFE for assignment project)
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
    | Count currently enrolled students (status = ENROLLED)
    |----------------------------------------------------------------------
    */
    public function enrolledStudentsCount(): int
    {
        return $this->students()
            ->wherePivot('status', 'ENROLLED')
            ->count();
    }

    /*
    |----------------------------------------------------------------------
    | Check if module has reached maximum student capacity (10)
    |----------------------------------------------------------------------
    */
    public function isFull(): bool
    {
        return $this->enrolledStudentsCount() >= 10;
    }
}
