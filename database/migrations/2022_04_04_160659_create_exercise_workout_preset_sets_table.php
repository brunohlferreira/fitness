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
        Schema::create('exercise_workout_preset_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_workout_preset_id')->constrained('exercise_workout_preset')->onDelete('cascade');;
            $table->unsignedTinyInteger('position');
            $table->unsignedSmallInteger('repetitions')->nullable();
            $table->unsignedDecimal('weight')->nullable();
            $table->unsignedSmallInteger('distance')->nullable();
            $table->unsignedSmallInteger('calories')->nullable();
            $table->unsignedSmallInteger('minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercise_workout_preset_sets');
    }
};
