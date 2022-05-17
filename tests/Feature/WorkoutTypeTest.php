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
        $response = $this->get('/workout-types');

        $response->assertRedirect('/login');
    }

    public function test_users_can_not_visit_workout_types()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workout-types');

        $response->assertStatus(403);
    }

    public function test_admins_can_visit_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->get('/workout-types');

        $response->assertStatus(200);
    }

    public function test_admins_can_create_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->post('/workout-types', ['name' => 'New workout type']);

        $this->assertEquals(1, count(WorkoutType::where('name', 'New workout type')->get()));
    }

    public function test_admins_can_edit_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $workoutType = WorkoutType::where('name', 'New workout type')->get();

        $this->assertEquals(1, count($workoutType));

        $response = $this->actingAs($user)->put("/workout-types/{$workoutType->first()->id}", [
            'name' => 'Edited workout type',
        ]);

        $this->assertEquals(1, count(WorkoutType::where('name', 'Edited workout type')->get()));
    }

    public function test_admins_can_delete_workout_types()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $workoutType = WorkoutType::where('name', 'Edited workout type')->get();

        $this->assertEquals(1, count($workoutType));

        $response = $this->actingAs($user)->delete("/workout-types/{$workoutType->first()->id}");

        $this->assertEquals(0, count(WorkoutType::where('name', 'Edited workout type')->get()));
    }
}
