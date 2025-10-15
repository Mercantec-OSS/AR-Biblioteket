<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationsTable extends Migration
{
    public function up()
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
            $table->string('color');
        });
    }

    public function down()
    {
        Schema::dropIfExists('educations');
    }
}
