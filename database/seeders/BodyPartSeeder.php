<?php

namespace Database\Seeders;

use App\Models\BodyPart;
use Illuminate\Database\Seeder;

class BodyPartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BodyPart::create(['name' => 'Triceps']);
        BodyPart::create(['name' => 'Chest']);
        BodyPart::create(['name' => 'Shoulders']);
        BodyPart::create(['name' => 'Biceps']);
        BodyPart::create(['name' => 'Core']);
        BodyPart::create(['name' => 'Back']);
        BodyPart::create(['name' => 'Dorsal']);
        BodyPart::create(['name' => 'Lombar']);
        BodyPart::create(['name' => 'Forearms']);
        BodyPart::create(['name' => 'Quadriceps']);
        BodyPart::create(['name' => 'Glutes']);
        BodyPart::create(['name' => 'Shins']);
        BodyPart::create(['name' => 'Cardio']);
    }
}
