<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('education_vrmodel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vrmodel_id')->references('id')->on('vrmodels');
            $table->foreignId('education_id')->references('id')->on('educations');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_vrmodel');
    }
};
