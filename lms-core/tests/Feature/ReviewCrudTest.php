<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_index()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/reviews');
        $response->assertStatus(200);
        $response->assertSee('Review');
    }

    public function test_review_create()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/reviews/create');
        $response->assertStatus(200);
        $response->assertSee('Add Review'); // Adjusted to match Blade template
    }

    public function test_review_store_valid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $book = \App\Models\Buku::factory()->create();
        $member = \App\Models\Member::factory()->create();
        $data = [
            'book_id' => $book->book_id,
            'member_id' => $member->member_id,
            'rating' => 5,
            'review_text' => 'Great book!',
        ];
        $response = $this->post('/reviews', $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('review_buku', ['book_id' => $book->book_id, 'member_id' => $member->member_id, 'rating' => 5, 'review_text' => 'Great book!']);
    }

    public function test_review_store_invalid()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $data = [
            'book_id' => null,
            'rating' => null,
        ];
        $response = $this->post('/reviews', $data);
        $response->assertSessionHasErrors(['book_id', 'rating']);
    }

    public function test_review_show()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $review = \App\Models\Review_Buku::factory()->create();
        $response = $this->get('/reviews/' . $review->review_id);
        $response->assertStatus(200);
        $response->assertSee((string) $review->review_text); // Adjust as needed
    }

    public function test_review_update()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $review = \App\Models\Review_Buku::factory()->create([
            'rating' => 3,
            'review_text' => 'Old review',
        ]);
        $data = [
            'rating' => 4,
            'review_text' => 'Updated review',
        ];
        $response = $this->put('/reviews/' . $review->review_id, $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('review_buku', [
            'review_id' => $review->review_id,
            'rating' => 4,
            'review_text' => 'Updated review',
        ]);
    }

    public function test_review_delete(): void
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $review = \App\Models\Review_Buku::factory()->create();
        $response = $this->delete('/reviews/' . $review->review_id);
        $response->assertStatus(302);
        $this->assertDatabaseMissing('review_buku', ['review_id' => $review->review_id]);
    }
}
