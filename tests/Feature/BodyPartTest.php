<?php

namespace Tests\Feature;

use App\Models\BodyPart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BodyPartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_body_parts()
    {
        $this
            ->get(route('body-parts.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_not_visit_body_parts()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('body-parts.index'))
            ->assertForbidden();
    }

    public function test_admins_can_visit_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->get(route('body-parts.index'))
            ->assertOk();
    }

    public function test_admins_can_create_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->post(route('body-parts.store'),
                ['name' => 'New body part']
            )
            ->assertRedirect(route('body-parts.index'));
    }

    public function test_admins_can_edit_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->put(route('body-parts.update', BodyPart::where('name', 'New body part')->first()->id), [
                'name' => 'Edited body part',
            ])
            ->assertRedirect(route('body-parts.index'));
    }

    public function test_admins_can_delete_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->delete(route('body-parts.destroy', BodyPart::where('name', 'Edited body part')->first()->id))
            ->assertNoContent();
    }
}
