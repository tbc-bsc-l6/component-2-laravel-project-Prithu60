<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active', // module availability (archive toggle)
    ];

    /*
    |--------------------------------------------------------------------------
    | Teachers assigned to this module
    |--------------------------------------------------------------------------
    */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_teacher')
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Students enrolled in this module (CORE RELATION)
    |--------------------------------------------------------------------------
    */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_user')
                    ->withPivot([
                        'enrolled_at',
                        'status',        // ENROLLED | PASS | FAIL
                        'completed_at',
                    ])
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (used across Student / Teacher / Admin)
    |--------------------------------------------------------------------------
    */

    // Count ONLY currently enrolled students
    public function enrolledStudentsCount(): int
    {
        return $this->students()
                    ->wherePivot('status', 'ENROLLED')
                    ->count();
    }

    // Module capacity check (max 10)
    public function isFull(): bool
    {
        return $this->enrolledStudentsCount() >= 10;
    }

    // Check if module is available for enrollment
    public function isAvailable(): bool
    {
        return $this->is_active && !$this->isFull();
    }
}
