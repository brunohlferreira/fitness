<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExerciseTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_exercises()
    {
        $response = $this->get('/exercises');

        $response->assertRedirect('/login');
    }

    public function test_users_can_visit_exercises()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/exercises');

        $response->assertStatus(200);
    }

    public function test_admins_can_create_exercises()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->post('/exercises', ['name' => 'New exercise', 'bilateral' => 1]);

        $this->assertEquals(1, count(Exercise::where('name', 'New exercise')->get()));
    }

    public function test_admins_can_edit_exercises()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $exercise = Exercise::where('name', 'New exercise')->get();

        $this->assertEquals(1, count($exercise));

        $response = $this->actingAs($user)->put("/exercises/{$exercise->first()->id}", [
            'name' => 'Edited exercise',
            'bilateral' => 1,
        ]);

        $this->assertEquals(1, count(Exercise::where('name', 'Edited exercise')->get()));
    }

    public function test_admins_can_delete_exercises()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $exercise = Exercise::where('name', 'Edited exercise')->get();

        $this->assertEquals(1, count($exercise));

        $response = $this->actingAs($user)->delete("/exercises/{$exercise->first()->id}");

        $this->assertEquals(0, count(Exercise::where('name', 'Edited exercise')->get()));
    }
}
