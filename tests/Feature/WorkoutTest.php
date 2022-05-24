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
        $this
            ->get(route('workouts.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_visit_workouts()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('workouts.index'))
            ->assertOk();
    }

    public function test_users_can_create_workouts()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->post(route('workouts.store'), [
                'name' => 'New workout',
                'date' => date('Y-m-d H:i:s'),
                'level' => 1,
                'time_cap' => 0,
                'workout_type_id' => 1,
            ])
            ->assertRedirect(route('workouts.index'));
    }

    public function test_users_can_not_edit_workouts_from_other_members()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->put(route('workouts.update', Workout::where('name', 'New workout')->first()->id), [
                'name' => 'Edited workout',
                'date' => date('Y-m-d H:i:s'),
                'level' => 1,
                'time_cap' => 0,
                'workout_type_id' => 1,
            ])
            ->assertForbidden();
    }

    public function test_users_can_edit_own_workouts()
    {
        $workout = Workout::where('name', 'New workout')->first();

        $this
            ->actingAs($workout->createdBy)
            ->put(route('workouts.update', $workout->id), [
                'name' => 'Edited workout',
                'date' => date('Y-m-d H:i:s'),
                'level' => 1,
                'time_cap' => 0,
                'workout_type_id' => 1,
            ])
            ->assertRedirect(route('workouts.index'));
    }

    public function test_users_can_not_delete_workouts_from_other_members()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->delete(route('workouts.destroy', Workout::where('name', 'Edited workout')->first()->id))
            ->assertForbidden();
    }

    public function test_users_can_delete_own_workouts()
    {
        $workout = Workout::where('name', 'Edited workout')->first();

        $this
            ->actingAs($workout->createdBy)
            ->delete(route('workouts.destroy', $workout->id))
            ->assertNoContent();
    }
}
