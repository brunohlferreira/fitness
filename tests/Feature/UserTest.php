<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_users()
    {
        $this
            ->get(route('users.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_not_visit_users()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('users.index'))
            ->assertStatus(403);
    }

    public function test_admins_can_visit_users()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->get(route('users.index'))
            ->assertOk();
    }

    public function test_admins_can_create_users()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@test.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertRedirect(route('users.index'));
    }

    public function test_admins_can_edit_roles()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $newUser = User::where('email', 'newuser@test.com')->get();

        $this
            ->actingAs($user)
            ->put(route('users.update-role', $newUser->first()->id), [
                'role' => 1,
            ])
            ->assertRedirect(route('users.index'));
    }
}
