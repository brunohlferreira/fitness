<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Equipment::create(['name' => 'None']);
        Equipment::create(['name' => 'Bench']);
        Equipment::create(['name' => 'Weight Bar']);
        Equipment::create(['name' => 'Dumbbell']);
        Equipment::create(['name' => 'Kettlebell']);
        Equipment::create(['name' => 'TRX']);
        Equipment::create(['name' => 'Cable Machine']);
        Equipment::create(['name' => 'Pull Up Bar']);
        Equipment::create(['name' => 'Climb Rope']);
        Equipment::create(['name' => 'Rings']);
        Equipment::create(['name' => 'Medicine Ball']);
        Equipment::create(['name' => 'Pilates Ball']);
        Equipment::create(['name' => 'Bosu']);
        Equipment::create(['name' => 'Resistance Bands']);
        Equipment::create(['name' => 'Jump Rope']);
        Equipment::create(['name' => 'Treadmill']);
        Equipment::create(['name' => 'Ab Wheel']);
        Equipment::create(['name' => 'Rowing Machine']);
        Equipment::create(['name' => 'Resistance Elastics']);
    }
}
