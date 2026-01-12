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
        Schema::table('vrmodels', function (Blueprint $table) {
            // Add a status column with default value "Test"
            $table->string('status')->default('Test')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vrmodels', function (Blueprint $table) {
            // Remove the status column if migration is rolled back
            $table->dropColumn('status');
        });
    }
};