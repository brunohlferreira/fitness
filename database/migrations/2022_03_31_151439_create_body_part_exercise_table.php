<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_part_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('body_part_id')->constrained('body_parts');
            $table->foreignId('exercise_id')->constrained('exercises');
            $table->unsignedTinyInteger('impact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_part_exercise');
    }
};
