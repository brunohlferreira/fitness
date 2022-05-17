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
        $response = $this->get('/users');

        $response->assertRedirect('/login');
    }

    public function test_users_can_not_visit_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(403);
    }

    public function test_admins_can_visit_users()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
    }

    public function test_admins_can_create_users()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $newUser = [
            'name' => 'New User',
            'email' => 'newuser@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->actingAs($user)->post('/users', $newUser);

        $this->assertEquals(1, count(User::where('email', $newUser['email'])->get()));
    }

    public function test_admins_can_edit_roles()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $newUser = User::where('email', 'newuser@test.com')->get();

        $this->assertEquals(1, count($newUser));

        $response = $this->actingAs($user)->put("/users/{$newUser->first()->id}/roles", ['role' => 1]);

        $this->assertEquals(1, count($newUser->first()->roles));
    }
}
