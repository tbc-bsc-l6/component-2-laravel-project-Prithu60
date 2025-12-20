<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'available',
    ];

    /**
     * Students enrolled in this module
     */
    public function students()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['student_start_date', 'completion_date', 'pass_fail'])
            ->withTimestamps();
    }

    /**
     * Teachers assigned to this module
     */
    public function teachers()
    {
        return $this->belongsToMany(
            User::class,
            'module_teacher',
            'module_id',
            'user_id'
        )->withTimestamps();
    }
}
