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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamp('date');
            $table->unsignedTinyInteger('level');
            $table->unsignedTinyInteger('time_cap');
            $table->string('score')->nullable();
            $table->foreignId('workout_type_id')->constrained('workout_types');
            $table->foreignId('workout_preset_id')->nullable()->constrained('workout_presets');
            $table->foreignId('created_by')->constrained('users');
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
        Schema::dropIfExists('workouts');
    }
};
