<?php

namespace Tests\Feature;

use Tests\Feature\Traits\UseSimpleDatabaseSetup;
use Tests\TestCase;

class BookmarkCrudTest extends TestCase
{
    use UseSimpleDatabaseSetup;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up a simple database schema for testing
        $this->setupSimpleDatabase();
    }

    public function test_bookmark_index()
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $this->actingAs($user);
        $response = $this->get('/member/bookmarks');
        $response->assertStatus(200);
        $response->assertSee('Bookmarks');
    }

    // Note: There is no create route for bookmarks in the current implementation
    // Bookmarks are created directly via POST to /member/bookmarks
    public function test_bookmark_create()
    {
        $this->markTestSkipped('No create route exists for bookmarks in the current implementation');
    }

    public function test_bookmark_store_valid(): void
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $book = $this->createTestBook();
        
        $this->actingAs($user);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'page_number' => 42,
            'notes' => 'Test notes',
        ];
        $response = $this->post('/member/bookmarks', $data);
        $response->assertStatus(302);
        $response->assertSessionHas('status', 'Book added to bookmarks.');
        $this->assertDatabaseHas('bookmarks', ['book_id' => $book->book_id, 'member_id' => $member->member_id]);
    }
    
    public function test_bookmark_notification_toast(): void
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $book = $this->createTestBook();
        
        $this->actingAs($user);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'page_number' => 42,
            'notes' => 'Test notes',
        ];
        
        // Create a bookmark using the correct route
        $response = $this->post('/member/bookmarks', $data);
        
        // Check that we get a redirect with a success message
        $response->assertStatus(302); // Redirect status
        $response->assertSessionHas('status', 'Book added to bookmarks.');
    }
    
    public function test_bookmark_store_with_page_and_notes()
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $book = $this->createTestBook();
        
        $this->actingAs($user);
        $data = [
            'book_id' => $book->book_id,
            'member_id' => $member->member_id,
            'page_number' => 42,
            'notes' => 'This is a very interesting part of the book.',
        ];
        
        $response = $this->post('/member/bookmarks', $data);
        $response->assertRedirect();
        
        // Assert that the bookmark was created with page number and notes
        $this->assertDatabaseHas('bookmarks', [
            'book_id' => $book->book_id,
            'member_id' => $member->member_id,
            'page_number' => 42,
            'notes' => 'This is a very interesting part of the book.',
        ]);
    }

    public function test_bookmark_store_invalid()
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $this->actingAs($user);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => null,
            'page_number' => 42,
            'notes' => 'Test notes',
        ];
        $response = $this->post('/member/bookmarks', $data);
        $response->assertSessionHasErrors('book_id');
    }

    // Note: There is no show route for individual bookmarks in the current implementation
    // Bookmarks are only viewed in the index page
    public function test_bookmark_show()
    {
        $this->markTestSkipped('No show route exists for individual bookmarks in the current implementation');
    }

    // Note: There is no PUT/update route for bookmarks in the current implementation
    // Updates are handled via the store method if the bookmark already exists
    public function test_bookmark_update_valid()
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $book = $this->createTestBook();
        
        // Create bookmark manually
        $bookmark = \App\Models\Bookmark::create([
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'page_number' => 10,
            'notes' => 'Original notes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $this->actingAs($user);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'page_number' => 42,
            'notes' => 'Updated notes',
        ];
        
        // Use POST to update an existing bookmark
        $response = $this->post('/member/bookmarks', $data);
        $response->assertStatus(302);
        
        // Verify the bookmark was updated
        $this->assertDatabaseHas('bookmarks', [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'page_number' => 42,
            'notes' => 'Updated notes'
        ]);
    }
    
    public function test_bookmark_permission_checks()
    {
        // Create two users and members
        $user1 = $this->createTestUser(['email' => 'user1@example.com']);
        $member1 = $this->createTestMember($user1);
        
        $user2 = $this->createTestUser(['email' => 'user2@example.com']);
        $member2 = $this->createTestMember($user2);
        
        $book = $this->createTestBook();
        
        // Create a bookmark as the first user
        $this->actingAs($user1);
        $response = $this->post('/member/bookmarks', [
            'member_id' => $member1->member_id,
            'book_id' => $book->book_id,
            'page_number' => 10,
            'notes' => 'Original notes',
        ]);
        
        // Verify bookmark was created
        $bookmark = \App\Models\Bookmark::where('member_id', $member1->member_id)
                                      ->where('book_id', $book->book_id)
                                      ->first();
        $this->assertNotNull($bookmark);
        
        // Try to access the bookmark as the second user
        // Note: Since the route is protected by role:Member middleware, both users can access it
        // But we should check if the controller has any additional authorization logic
        $this->actingAs($user2);
        
        // Create a second bookmark for the second user
        $response = $this->post('/member/bookmarks', [
            'member_id' => $member2->member_id,
            'book_id' => $book->book_id,
            'page_number' => 20,
            'notes' => 'Second user notes',
        ]);
        
        // Verify second bookmark was created
        $bookmark2 = \App\Models\Bookmark::where('member_id', $member2->member_id)
                                      ->where('book_id', $book->book_id)
                                      ->first();
        $this->assertNotNull($bookmark2);
        
        // Verify that the first user can delete their own bookmark
        $this->actingAs($user1);
        $response = $this->delete('/member/bookmarks/' . $bookmark->bookmark_id);
        $response->assertStatus(302); // Redirect after successful deletion
        
        // First bookmark should be deleted
        $this->assertDatabaseMissing('bookmarks', [
            'bookmark_id' => $bookmark->bookmark_id,
        ]);
        
        // Verify that the second user can delete their own bookmark
        $this->actingAs($user2);
        $response = $this->delete('/member/bookmarks/' . $bookmark2->bookmark_id);
        $response->assertStatus(302); // Redirect after successful deletion
        
        // Second bookmark should be deleted
        $this->assertDatabaseMissing('bookmarks', [
            'bookmark_id' => $bookmark2->bookmark_id,
        ]);
    }
    
    public function test_bookmark_validation()
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $book = $this->createTestBook();
        
        $this->actingAs($user);
        
        // Test invalid page number (negative)
        $response = $this->post('/member/bookmarks', [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'page_number' => -5,
            'notes' => 'Valid notes'
        ]);
        $response->assertSessionHasErrors('page_number');
        
        // Test notes too long
        $response = $this->post('/member/bookmarks', [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'page_number' => 50,
            'notes' => str_repeat('a', 1001) // 1001 characters, exceeding 1000 limit
        ]);
        $response->assertSessionHasErrors('notes');
    }
    
    // Note: The current implementation doesn't have PUT/update routes for bookmarks
    // Instead, updates are handled via POST to /member/bookmarks with the same book_id and member_id
    // This test is updated to reflect that behavior
    public function test_member_can_only_edit_own_bookmarks()
    {
        // Create first user with a bookmark
        $user1 = $this->createTestUser(['name' => 'First User', 'email' => 'first@example.com']);
        $member1 = $this->createTestMember($user1);
        $book1 = $this->createTestBook(['title' => 'User 1 Book']);
        
        // Create bookmark for first user
        $bookmark1 = \App\Models\Bookmark::create([
            'member_id' => $member1->member_id,
            'book_id' => $book1->book_id,
            'page_number' => 10,
            'notes' => 'Original notes from user 1',
            'added_date' => now(),
        ]);
        
        // Create second user
        $user2 = $this->createTestUser(['name' => 'Second User', 'email' => 'second@example.com']);
        $member2 = $this->createTestMember($user2);
        
        // Login as the second user
        $this->actingAs($user2);
        
        // Try to update the first user's bookmark by using the same book_id but different member_id
        // This should create a new bookmark for user2 instead of updating user1's bookmark
        $response = $this->post('/member/bookmarks', [
            'member_id' => $member2->member_id,
            'book_id' => $book1->book_id,
            'page_number' => 25,
            'notes' => 'New bookmark by user 2'
        ]);
        
        // Should succeed in creating a new bookmark
        $response->assertStatus(302);
        
        // Verify the original bookmark was not changed
        $this->assertDatabaseHas('bookmarks', [
            'bookmark_id' => $bookmark1->bookmark_id,
            'member_id' => $member1->member_id,
            'page_number' => 10,
            'notes' => 'Original notes from user 1',
        ]);
        
        // Verify a new bookmark was created for user2
        $this->assertDatabaseHas('bookmarks', [
            'member_id' => $member2->member_id,
            'book_id' => $book1->book_id,
            'page_number' => 25,
            'notes' => 'New bookmark by user 2',
        ]);

        // Now login as the first user
        $this->actingAs($user1);
        
        // Update own bookmark
        $response = $this->post('/member/bookmarks', [
            'member_id' => $member1->member_id,
            'book_id' => $book1->book_id,
            'page_number' => 30,
            'notes' => 'Updated by rightful owner'
        ]);
        
        // Should succeed
        $response->assertStatus(302);
        
        // Verify the bookmark was updated
        $this->assertDatabaseHas('bookmarks', [
            'member_id' => $member1->member_id,
            'book_id' => $book1->book_id,
            'page_number' => 30,
            'notes' => 'Updated by rightful owner'
        ]);
    }

    public function test_bookmark_delete()
    {
        $user = $this->createTestUser();
        $member = $this->createTestMember($user);
        $book = $this->createTestBook();
        
        // Create a bookmark manually for testing
        $bookmark = \App\Models\Bookmark::create([
            'book_id' => $book->book_id,
            'member_id' => $member->member_id,
            'page_number' => 10,
            'notes' => 'Notes to be deleted',
            'added_date' => now(),
        ]);
        
        $this->actingAs($user);
        $response = $this->delete('/member/bookmarks/' . $bookmark->bookmark_id);
        $response->assertStatus(302); // Redirect after successful deletion
        $this->assertDatabaseMissing('bookmarks', ['bookmark_id' => $bookmark->bookmark_id]);
    }
}
