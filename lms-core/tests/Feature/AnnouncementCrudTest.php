<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_index()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/announcements');
        $response->assertStatus(200);
        $response->assertSee('Announcement');
    }

    public function test_announcement_create()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/announcements/create');
        $response->assertStatus(200);
        $response->assertSee('Add Announcement');
    }

    public function test_announcement_store_valid()
    {
        $admin = \App\Models\Admin::factory()->create();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'title' => 'New Announcement',
            'content' => 'Announcement Content',
            'status' => 'published',
            'post_date' => now(),
            'admin_id' => $admin->admin_id,
        ];
        $response = $this->post('/announcements', $data);
        $response->assertRedirect('/announcements');
        $this->assertDatabaseHas('pengumuman', ['title' => 'New Announcement']);
    }

    public function test_announcement_store_invalid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'title' => '',
        ];
        $response = $this->post('/announcements', $data);
        $response->assertSessionHasErrors('title');
    }

    public function test_announcement_show()
    {
        $admin = \App\Models\Admin::factory()->create();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $announcement = \App\Models\Pengumuman::factory()->create(['admin_id' => $admin->admin_id]);
        $response = $this->get('/announcements/' . $announcement->post_id);
        $response->assertStatus(200);
        $response->assertSee($announcement->title);
    }

    public function test_announcement_update()
    {
        $admin = \App\Models\Admin::factory()->create();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $announcement = \App\Models\Pengumuman::factory()->create(['title' => 'Old Title', 'admin_id' => $admin->admin_id]);
        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'status' => 'draft',
            'post_date' => now(),
            'admin_id' => $admin->admin_id,
        ];
        $response = $this->put('/announcements/' . $announcement->post_id, $data);
        $response->assertRedirect('/announcements');
        $this->assertDatabaseHas('pengumuman', ['post_id' => $announcement->post_id, 'title' => 'Updated Title']);
    }

    public function test_announcement_delete()
    {
        $admin = \App\Models\Admin::factory()->create();
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $announcement = \App\Models\Pengumuman::factory()->create(['admin_id' => $admin->admin_id]);
        $response = $this->delete('/announcements/' . $announcement->post_id);
        $response->assertRedirect('/announcements');
        $this->assertDatabaseMissing('pengumuman', ['post_id' => $announcement->post_id]);
    }
}
