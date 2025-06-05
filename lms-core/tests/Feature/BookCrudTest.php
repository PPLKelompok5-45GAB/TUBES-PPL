<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_index()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/books');
        $response->assertStatus(200);
        $response->assertSee('Books');
    }

    public function test_book_create()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/books/create');
        $response->assertStatus(200);
        $response->assertSee('Add Book');
    }

    public function test_book_store_valid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $category = \App\Models\Kategori::factory()->create();
        $data = [
            'title' => 'New Book',
            'author' => 'Author',
            'isbn' => '1234567890',
            'category_id' => $category->category_id,
            'stock' => 5,
            'description' => 'A new book',
        ];
        $response = $this->post('/books', $data);
        $response->assertRedirect('/books');
        $this->assertDatabaseHas('buku', ['title' => 'New Book']);
    }

    public function test_book_store_invalid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'title' => '',
        ];
        $response = $this->post('/books', $data);
        $response->assertSessionHasErrors('title');
    }

    public function test_book_show()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $book = \App\Models\Buku::factory()->create();
        $response = $this->get('/books/' . $book->book_id);
        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_book_update()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $category = \App\Models\Kategori::factory()->create();
        $book = \App\Models\Buku::factory()->create([
            'title' => 'Old Title',
            'isbn' => '1234567890',
            'category_id' => $category->category_id,
            'stock' => 10,
            'description' => 'Old description',
        ]);
        $data = [
            'title' => 'Updated Title',
            'author' => $book->author,
            'isbn' => '1234567899',
            'category_id' => $category->category_id,
            'stock' => 15,
            'description' => 'Updated description',
        ];
        $response = $this->put('/books/' . $book->book_id, $data);
        $response->assertRedirect('/books');
        $this->assertDatabaseHas('buku', ['book_id' => $book->book_id, 'title' => 'Updated Title']);
    }

    public function test_book_delete()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $book = \App\Models\Buku::factory()->create();
        $response = $this->delete('/books/' . $book->book_id);
        $response->assertRedirect('/books');
        $this->assertDatabaseMissing('buku', ['book_id' => $book->book_id]);
    }
}
