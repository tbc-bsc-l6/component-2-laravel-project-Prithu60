<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRole;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles first
        $this->call(UserRoleSeeder::class);

        // FIX: use `role` column (NOT `name`)
        $adminRole = UserRole::where('role', 'admin')->first();
        $teacherRole = UserRole::where('role', 'teacher')->first();
        $studentRole = UserRole::where('role', 'student')->first();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'user_role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'),
            'user_role_id' => $teacherRole->id,
        ]);

        User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'user_role_id' => $studentRole->id,
        ]);
    }
}
