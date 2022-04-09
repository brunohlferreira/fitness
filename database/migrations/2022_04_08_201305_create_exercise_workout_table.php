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
        Schema::create('exercise_workout', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained();
            $table->foreignId('workout_id')->constrained('workouts');
            $table->unsignedTinyInteger('position');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('exercise_workout');
    }
};
