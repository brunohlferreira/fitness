<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_workouts()
    {
        $response = $this->get('/workouts');

        $response->assertRedirect('/login');
    }

    public function test_users_can_visit_workouts()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/workouts');

        $response->assertStatus(200);
    }

    public function test_users_can_create_workouts()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/workouts', [
            'name' => 'New workout',
            'date' => date('Y-m-d H:i:s'),
            'level' => 1,
            'time_cap' => 0,
            'workout_type_id' => 1,
        ]);

        $this->assertEquals(1, count(Workout::where('name', 'New workout')->get()));
    }

    public function test_users_can_not_edit_workouts_from_other_members()
    {
        $user = User::factory()->create();

        $workout = Workout::where('name', 'New workout')->get();

        $this->assertEquals(1, count($workout));

        $response = $this->actingAs($user)->put("/workouts/{$workout->first()->id}", [
            'name' => 'Edited workout',
            'date' => date('Y-m-d H:i:s'),
            'level' => 1,
            'time_cap' => 0,
            'workout_type_id' => 1,
        ]);

        $this->assertEquals(0, count(Workout::where('name', 'Edited workout')->get()));
    }

    public function test_users_can_edit_own_workouts()
    {
        $workout = Workout::where('name', 'New workout')->get();

        $this->assertEquals(1, count($workout));

        $response = $this->actingAs($workout->first()->createdBy)->put("/workouts/{$workout->first()->id}", [
            'name' => 'Edited workout',
            'date' => date('Y-m-d H:i:s'),
            'level' => 1,
            'time_cap' => 0,
            'workout_type_id' => 1,
        ]);

        $this->assertEquals(1, count(Workout::where('name', 'Edited workout')->get()));
    }

    public function test_users_can_not_delete_workouts_from_other_members()
    {
        $user = User::factory()->create();

        $workout = Workout::where('name', 'Edited workout')->get();

        $this->assertEquals(1, count($workout));

        $response = $this->actingAs($user)->delete("/workouts/{$workout->first()->id}");

        $this->assertEquals(1, count(Workout::where('name', 'Edited workout')->get()));
    }

    public function test_users_can_delete_own_workouts()
    {
        $workout = Workout::where('name', 'Edited workout')->get();

        $this->assertEquals(1, count($workout));

        $response = $this->actingAs($workout->first()->createdBy)->delete("/workouts/{$workout->first()->id}");

        $this->assertEquals(0, count(Workout::where('name', 'Edited workout')->get()));
    }
}
