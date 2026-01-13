<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('module_user', function (Blueprint $table) {
            $table->timestamp('teacher_assigned_at')
                  ->nullable()
                  ->after('pass_fail');
        });
    }

    public function down(): void
    {
        Schema::table('module_user', function (Blueprint $table) {
            $table->dropColumn('teacher_assigned_at');
        });
    }
};
