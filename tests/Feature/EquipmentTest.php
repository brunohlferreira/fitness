<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_equipments()
    {
        $this
            ->get(route('equipments.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_not_visit_equipments()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('equipments.index'))
            ->assertForbidden();
    }

    public function test_admins_can_visit_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->get(route('equipments.index'))
            ->assertOk();
    }

    public function test_admins_can_create_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->post(route('equipments.store'),
                ['name' => 'New equipment']
            )
            ->assertRedirect(route('equipments.index'));
    }

    public function test_admins_can_edit_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->put(route('equipments.update', Equipment::where('name', 'New equipment')->first()->id), [
                'name' => 'Edited equipment',
            ])
            ->assertRedirect(route('equipments.index'));
    }

    public function test_admins_can_delete_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $this
            ->actingAs($user)
            ->delete(route('equipments.destroy', Equipment::where('name', 'Edited equipment')->first()->id))
            ->assertNoContent();
    }
}
