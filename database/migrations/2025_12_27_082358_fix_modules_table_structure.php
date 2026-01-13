<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {

            // Rename existing column
            $table->renameColumn('module', 'name');

            // Add missing columns
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {

            // Revert column name
            $table->renameColumn('name', 'module');

            // Drop added columns
            $table->dropColumn(['description', 'is_active']);
        });
    }
};

