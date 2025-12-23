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

    // Teacher assignment (pivot table already exists: module_teacher)
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_teacher');
    }

    // Student enrolments (pivot table already exists: module_user)
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'module_user');
    }
}
