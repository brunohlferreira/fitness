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
        $response = $this->get('/body-parts');

        $response->assertRedirect('/login');
    }

    public function test_users_can_not_visit_body_parts()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/body-parts');

        $response->assertStatus(403);
    }

    public function test_admins_can_visit_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->get('/body-parts');

        $response->assertStatus(200);
    }

    public function test_admins_can_create_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $response = $this->actingAs($user)->post('/body-parts', ['name' => 'New body part']);

        $this->assertEquals(1, count(BodyPart::where('name', 'New body part')->get()));
    }

    public function test_admins_can_edit_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $bodyPart = BodyPart::where('name', 'New body part')->get();

        $this->assertEquals(1, count($bodyPart));

        $response = $this->actingAs($user)->put("/body-parts/{$bodyPart->first()->id}", [
            'name' => 'Edited body part',
        ]);

        $this->assertEquals(1, count(BodyPart::where('name', 'Edited body part')->get()));
    }

    public function test_admins_can_delete_body_parts()
    {
        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $user = User::factory()->create()->assignRole($role);

        $bodyPart = BodyPart::where('name', 'Edited body part')->get();

        $this->assertEquals(1, count($bodyPart));

        $response = $this->actingAs($user)->delete("/body-parts/{$bodyPart->first()->id}");

        $this->assertEquals(0, count(BodyPart::where('name', 'Edited body part')->get()));
    }
}
