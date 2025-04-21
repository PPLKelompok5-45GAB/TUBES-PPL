<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        // Create an admin user
        $admin = \App\Models\User::factory()->create(['role' => 'Admin']);
        $this->actingAs($admin);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard'); // Assert role-specific content
    }

    public function test_member_can_access_dashboard()
    {
        // Create a member user
        $member = \App\Models\User::factory()->create(['role' => 'Member']);
        $this->actingAs($member);
        $response = $this->get('/member/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard'); // Assert member dashboard content
    }
}
