<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('education_vrmodel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vrmodel_id')->constrained('vrmodels')->onDelete('cascade');
            $table->foreignId('education_id')->constrained('educations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_vrmodel');
    }
};
