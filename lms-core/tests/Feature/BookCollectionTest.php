<?php

namespace tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\BookCollection;
use App\Models\User;

class BookCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_collections()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        BookCollection::factory()->count(2)->create();
        $response = $this->get(route('bookcollection.index'));
        $response->assertStatus(200);
    }

    public function test_store_creates_collection()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $data = [
            'name' => 'Test Collection',
            'description' => 'A test collection',
            'cover_image' => 'http://example.com/image.jpg',
        ];
        $response = $this->post(route('bookcollection.store'), $data);
        $response->assertRedirect(route('bookcollection.index'));
        $this->assertDatabaseHas('book_collections', ['name' => 'Test Collection']);
    }
}
