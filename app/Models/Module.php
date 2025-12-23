<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | Teachers assigned to module
    |--------------------------------------------------------------------------
    */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_teacher')
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Students enrolled in module (IMPORTANT)
    |--------------------------------------------------------------------------
    */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_user')
                    ->withPivot([
                        'student_start_date',
                        'completion_date',
                        'pass_fail',
                    ])
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (used everywhere)
    |--------------------------------------------------------------------------
    */

    // How many students are currently enrolled
    public function enrolledStudentsCount(): int
    {
        return $this->students()->count();
    }

    // Check if module is full (max 10 students)
    public function isFull(): bool
    {
        return $this->enrolledStudentsCount() >= 10;
    }
}
