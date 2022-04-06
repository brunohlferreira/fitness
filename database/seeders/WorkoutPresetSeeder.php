<?php

namespace Database\Seeders;

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
        WorkoutPreset::create(['name' => 'Murph', 'description' => null, 'level' => 3, 'time_cap' => 0, 'workout_type_id' => 3, 'created_by' => 1, 'updated_by' => null]);
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
    }
}
