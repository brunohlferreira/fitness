<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_not_visit_dashboard()
    {
        $this
            ->get(route('dashboard.index'))
            ->assertRedirect(route('login'));
    }

    public function test_users_can_visit_dashboard()
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('dashboard.index'))
            ->assertOk();
    }
}
