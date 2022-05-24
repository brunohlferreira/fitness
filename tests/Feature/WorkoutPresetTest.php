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
        $this
            ->get(route('workout-presets.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_visit_workout_presets()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('workout-presets.index'))
            ->assertOk();
    }

    public function test_admins_can_create_workout_presets()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->post(route('workout-presets.store'), [
                'name' => 'New workout',
                'level' => 1,
                'time_cap' => 0,
                'workout_type_id' => 1,
            ])
            ->assertRedirect(route('workout-presets.index'));
    }

    public function test_admins_can_edit_workout_presets()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->put(route('workout-presets.update', WorkoutPreset::where('name', 'New workout')->first()->id), [
                'name' => 'Edited workout',
                'level' => 1,
                'time_cap' => 0,
                'workout_type_id' => 1,
            ])
            ->assertRedirect(route('workout-presets.index'));
    }

    public function test_admins_can_delete_workout_presets()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->delete(route('workout-presets.destroy', WorkoutPreset::where('name', 'Edited workout')->first()->id))
            ->assertNoContent();
    }
}
