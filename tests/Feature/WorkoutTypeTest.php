<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkoutType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WorkoutTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_workout_types()
    {
        $this
            ->get(route('workout-types.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_not_visit_workout_types()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('workout-types.index'))
            ->assertForbidden();
    }

    public function test_admins_can_visit_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->get(route('workout-types.index'))
            ->assertOk();
    }

    public function test_admins_can_create_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->post(route('workout-types.store'),
                ['name' => 'New workout type']
            )
            ->assertRedirect(route('workout-types.index'));
    }

    public function test_admins_can_edit_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->put(route('workout-types.update', WorkoutType::where('name', 'New workout type')->first()->id), [
                'name' => 'Edited workout type',
            ])
            ->assertRedirect(route('workout-types.index'));
    }

    public function test_admins_can_delete_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->delete(route('workout-types.destroy', WorkoutType::where('name', 'Edited workout type')->first()->id))
            ->assertNoContent();
    }
}
