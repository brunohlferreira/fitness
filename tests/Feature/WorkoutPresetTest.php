<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkoutPreset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WorkoutPresetTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_workout_presets()
    {
        $response = $this->get('/workout-presets');

        $response->assertRedirect('/login');
    }

    public function test_users_can_visit_workout_presets()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workout-presets');

        $response->assertStatus(200);
    }

    public function test_admins_can_create_workout_presets()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->post('/workout-presets', [
            'name' => 'New workout',
            'level' => 1,
            'time_cap' => 0,
            'workout_type_id' => 1,
        ]);

        $this->assertEquals(1, count(WorkoutPreset::where('name', 'New workout')->get()));
    }

    public function test_admins_can_edit_workout_presets()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $workoutPreset = WorkoutPreset::where('name', 'New workout')->get();

        $this->assertEquals(1, count($workoutPreset));

        $response = $this->actingAs($user)->put("/workout-presets/{$workoutPreset->first()->id}", [
            'name' => 'Edited workout',
            'level' => 1,
            'time_cap' => 0,
            'workout_type_id' => 1,
        ]);

        $this->assertEquals(1, count(WorkoutPreset::where('name', 'Edited workout')->get()));
    }

    public function test_admins_can_delete_workout_presets()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $workoutPreset = WorkoutPreset::where('name', 'Edited workout')->get();

        $this->assertEquals(1, count($workoutPreset));

        $response = $this->actingAs($user)->delete("/workout-presets/{$workoutPreset->first()->id}");

        $this->assertEquals(0, count(WorkoutPreset::where('name', 'Edited workout')->get()));
    }
}
