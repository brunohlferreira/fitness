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
        WorkoutType::create(['name' => 'Normal (No time)']);
        WorkoutType::create(['name' => 'AMRAP (As many rounds as possible)']);
        WorkoutType::create(['name' => 'RFT (Rounds for time)']);
        WorkoutType::create(['name' => 'EMOM (Every minute on minute)']);
    }
}
