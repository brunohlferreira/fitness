<?php

namespace Database\Seeders;

use App\Models\BodyPart;
use App\Models\Exercise;
use App\Models\Equipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutPresetSet;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Exercise::create(['name' => 'Muscle-Up', 'bilateral' => 1, 'created_by' => 1]);
        Exercise::create(['name' => 'Run', 'bilateral' => 1, 'created_by' => 1]);
        Exercise::create(['name' => 'Pull-Up', 'bilateral' => 1, 'created_by' => 1]);
        Exercise::create(['name' => 'Push-Up', 'bilateral' => 1, 'created_by' => 1]);
        Exercise::create(['name' => 'Air Squat', 'bilateral' => 1, 'created_by' => 1]);
        Exercise::create(['name' => 'Burpee', 'bilateral' => 1, 'created_by' => 1]);
        Exercise::create(['name' => 'Jump Squat', 'bilateral' => 1, 'created_by' => 1]);

        Exercise::factory()->count(50)->create();

        $bodyParts = BodyPart::all();
        $equipments = Equipment::all();

        Exercise::all()->each(function ($exercise) use ($bodyParts, $equipments) {
            $bodyParts->random(rand(4, 6))->each(function ($bodyPart) use ($exercise) {
                DB::table('body_part_exercise')->insert([
                    'body_part_id' => $bodyPart->id,
                    'exercise_id' => $exercise->id,
                    'impact' => rand(1, 2),
                ]);
            });

            $exercise->equipments()->attach(
                $equipments->random(rand(1, 2))->pluck('id')->toArray()
            );
        });

        ExerciseWorkoutPreset::create(['exercise_id' => 2, 'workout_preset_id' => 1, 'position' => 1]);
        ExerciseWorkoutPreset::create(['exercise_id' => 3, 'workout_preset_id' => 1, 'position' => 2]);
        ExerciseWorkoutPreset::create(['exercise_id' => 4, 'workout_preset_id' => 1, 'position' => 3]);
        ExerciseWorkoutPreset::create(['exercise_id' => 5, 'workout_preset_id' => 1, 'position' => 4]);
        ExerciseWorkoutPreset::create(['exercise_id' => 2, 'workout_preset_id' => 1, 'position' => 5]);

        ExerciseWorkoutPresetSet::create(['exercise_workout_preset_id' => 1, 'position' => 1, 'repetitions' => 0, 'weight' => 0, 'distance' => 1600, 'calories' => 0, 'minutes' => 0]);
        ExerciseWorkoutPresetSet::create(['exercise_workout_preset_id' => 2, 'position' => 2, 'repetitions' => 100, 'weight' => 0, 'distance' => 0, 'calories' => 0, 'minutes' => 0]);
        ExerciseWorkoutPresetSet::create(['exercise_workout_preset_id' => 3, 'position' => 3, 'repetitions' => 200, 'weight' => 0, 'distance' => 0, 'calories' => 0, 'minutes' => 0]);
        ExerciseWorkoutPresetSet::create(['exercise_workout_preset_id' => 4, 'position' => 4, 'repetitions' => 300, 'weight' => 0, 'distance' => 0, 'calories' => 0, 'minutes' => 0]);
        ExerciseWorkoutPresetSet::create(['exercise_workout_preset_id' => 5, 'position' => 5, 'repetitions' => 0, 'weight' => 0, 'distance' => 1600, 'calories' => 0, 'minutes' => 0]);
    }
}
