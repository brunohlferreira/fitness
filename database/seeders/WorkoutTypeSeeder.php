<?php

namespace Database\Seeders;

use App\Models\WorkoutType;
use Illuminate\Database\Seeder;

class WorkoutTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkoutType::create(['name' => 'Normal', 'description' => 'No time']);
        WorkoutType::create(['name' => 'AMRAP', 'description' => 'As many rounds as possible']);
        WorkoutType::create(['name' => 'RFT', 'description' => 'Rounds for time']);
        WorkoutType::create(['name' => 'EMOM', 'description' => 'Every minute on minute']);
    }
}
