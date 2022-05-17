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
        $response = $this->get('/equipments');

        $response->assertRedirect('/login');
    }

    public function test_users_can_not_visit_equipments()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/equipments');

        $response->assertStatus(403);
    }

    public function test_admins_can_visit_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->get('/equipments');

        $response->assertStatus(200);
    }

    public function test_admins_can_create_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->post('/equipments', ['name' => 'New equipment']);

        $this->assertEquals(1, count(Equipment::where('name', 'New equipment')->get()));
    }

    public function test_admins_can_edit_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $equipment = Equipment::where('name', 'New equipment')->get();

        $this->assertEquals(1, count($equipment));

        $response = $this->actingAs($user)->put("/equipments/{$equipment->first()->id}", ['name' => 'Edited equipment']);

        $this->assertEquals(1, count(Equipment::where('name', 'Edited equipment')->get()));
    }

    public function test_admins_can_delete_equipments()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $equipment = Equipment::where('name', 'Edited equipment')->get();

        $this->assertEquals(1, count($equipment));

        $response = $this->actingAs($user)->delete("/equipments/{$equipment->first()->id}");

        $this->assertEquals(0, count(Equipment::where('name', 'Edited equipment')->get()));
    }
}
