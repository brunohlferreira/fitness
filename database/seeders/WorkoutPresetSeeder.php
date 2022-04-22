<?php

namespace Database\Seeders;

use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutPresetSet;
use App\Models\WorkoutPreset;
use Illuminate\Database\Seeder;

class WorkoutPresetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkoutPreset::create(['name' => 'Murph', 'description' => 'The workout consists of a mile run, followed by 100 pull-ups, 200 push-ups, 300 air squats, and ends with another mile run. These exercises can be performed in any order without any rules on repetitions. The recommended routine to use is 10 pull-ups, 20 push-ups and 30 air squats between the runs.', 'level' => 3, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Half Murph', 'description' => null, 'level' => 2, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Burpees and Squats', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Cindy', 'description' => null, 'level' => 2, 'time_cap' => 20, 'workout_type_id' => 2, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Push Ups, Legs and Cardio', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Legs, Abs, Cardio and Chest', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Push-Ups, Air Squats and Burpees', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'More than Cardio', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Pull-Ups and Push-Ups', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Burpees, Air Squats, Push-Ups and Sit-Ups (30-20-10)', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
        WorkoutPreset::create(['name' => 'Air Squats, Push-Ups and Sit-Ups (50 reps)', 'description' => null, 'level' => 1, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);

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
