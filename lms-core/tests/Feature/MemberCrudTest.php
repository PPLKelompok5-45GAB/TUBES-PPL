<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_index()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/members');
        $response->assertStatus(200);
        $response->assertSee('Member');
    }

    public function test_member_create()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/members/create');
        $response->assertStatus(200);
        $response->assertSee('Add Member');
    }

    public function test_member_store_valid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'name' => 'New Member',
            'email' => 'member@example.com',
            'password' => 'password',
            'status' => 'active',
            'membership_date' => now()->toDateString(),
            'phone' => '1234567890',
            'address' => '123 Main St',
        ];
        $response = $this->post('/members', $data);
        $response->assertRedirect('/members');
        $this->assertDatabaseHas('member', ['email' => 'member@example.com']);
    }

    public function test_member_store_invalid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'name' => '',
            'email' => 'not-an-email',
            'status' => '',
            'membership_date' => '',
            'phone' => '',
            'address' => '',
        ];
        $response = $this->post('/members', $data);
        $response->assertSessionHasErrors(['name', 'email', 'status', 'membership_date']);
    }

    public function test_member_show()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $member = \App\Models\Member::factory()->create();
        $response = $this->get('/members/' . $member->member_id);
        $response->assertStatus(200);
        $response->assertSee($member->name ?? $member->email);
    }

    public function test_member_update()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $member = \App\Models\Member::factory()->create(['name' => 'Old Name']);
        $data = [
            'name' => 'Updated Name',
            'email' => $member->email,
            'status' => $member->status,
            'membership_date' => $member->membership_date,
            'phone' => $member->phone,
            'address' => $member->address,
        ];
        $response = $this->put('/members/' . $member->member_id, $data);
        $response->assertRedirect('/members');
        $this->assertDatabaseHas('member', ['member_id' => $member->member_id, 'name' => 'Updated Name']);
    }

    public function test_member_delete()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $member = \App\Models\Member::factory()->create();
        $response = $this->delete('/members/' . $member->member_id);
        $response->assertRedirect('/members');
        $this->assertDatabaseMissing('member', ['member_id' => $member->member_id]);
    }
}
