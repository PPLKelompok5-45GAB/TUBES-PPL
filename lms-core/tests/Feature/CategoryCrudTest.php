<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_index()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/categories');
        $response->assertStatus(200);
        $response->assertSee('Category'); // Adjust to actual content
    }

    public function test_category_create()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/categories/create');
        $response->assertStatus(200);
        $response->assertSee('Add Category'); // Adjusted to match the actual view
    }

    public function test_category_store_valid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'category_name' => 'New Category',
        ];
        $response = $this->post('/categories', $data);
        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('kategori', $data);
    }

    public function test_category_store_invalid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'category_name' => '', // Invalid: required field
        ];
        $response = $this->post('/categories', $data);
        $response->assertSessionHasErrors('category_name');
    }

    public function test_category_show()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $category = \App\Models\Kategori::factory()->create();
        $response = $this->get('/categories/' . $category->category_id);
        $response->assertStatus(200);
        $response->assertSee($category->category_name);
    }

    public function test_category_update()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $category = \App\Models\Kategori::factory()->create(['category_name' => 'Old Name']);
        $data = ['category_name' => 'Updated Name'];
        $response = $this->put('/categories/' . $category->category_id, $data);
        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('kategori', ['category_id' => $category->category_id, 'category_name' => 'Updated Name']);
    }

    public function test_category_delete()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $category = \App\Models\Kategori::factory()->create();
        $response = $this->delete('/categories/' . $category->category_id);
        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('kategori', ['category_id' => $category->category_id]);
    }
}
