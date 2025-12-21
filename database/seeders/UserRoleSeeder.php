<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        UserRole::insert([
            ['name' => 'admin'],
            ['name' => 'teacher'],
            ['name' => 'student'],
            ['name' => 'old_student'],
        ]);
    }
}
