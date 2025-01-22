<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('education');
            $table->string('description');
            $table->foreignId('user_id')->constrainted(
                table: 'users', indexName: 'user_id'
            ); 
            $table->string('model_path'); 
            $table->string('image_path'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('models');
    }
}