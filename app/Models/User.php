<?php

namespace App\Models;

use App\Models\Module;   // ✅ IMPORTANT
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Modules this user teaches (TEACHER role)
     */
    public function teachingModules()
    {
        return $this->belongsToMany(
            Module::class,
            'module_teacher',
            'user_id',
            'module_id'
        )->withTimestamps();
    }
}
