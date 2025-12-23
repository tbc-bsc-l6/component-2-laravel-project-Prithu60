<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('module_user', function (Blueprint $table) {
        // rename for clarity
        $table->renameColumn('student_start_date', 'enrolled_at');
        $table->renameColumn('completion_date', 'completed_at');

        // improve pass/fail semantics
        $table->dropColumn('pass_fail');
        $table->enum('status', ['ENROLLED', 'PASS', 'FAIL'])
              ->default('ENROLLED');
    });
}

public function down(): void
{
    Schema::table('module_user', function (Blueprint $table) {
        $table->renameColumn('enrolled_at', 'student_start_date');
        $table->renameColumn('completed_at', 'completion_date');

        $table->dropColumn('status');
        $table->enum('pass_fail', ['PASS', 'FAIL'])->nullable();
    });
}
}