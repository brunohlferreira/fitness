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
        $this
            ->get(route('exercises.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_visit_exercises()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('exercises.index'))
            ->assertOk();
    }

    public function test_admins_can_create_exercises()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->post(route('exercises.store'),
                ['name' => 'New exercise', 'bilateral' => 1]
            )
            ->assertRedirect(route('exercises.index'));
    }

    public function test_admins_can_edit_exercises()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->put(route('exercises.update', Exercise::where('name', 'New exercise')->first()->id), [
                'name' => 'Edited exercise',
                'bilateral' => 1,
            ])
            ->assertRedirect(route('exercises.index'));
    }

    public function test_admins_can_delete_exercises()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->delete(route('exercises.destroy', Exercise::where('name', 'Edited exercise')->first()->id))
            ->assertNoContent();
    }
}
