<?php

namespace Database\Seeders;

use App\Models\BodyPart;
use App\Models\Equipment;
use App\Models\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
