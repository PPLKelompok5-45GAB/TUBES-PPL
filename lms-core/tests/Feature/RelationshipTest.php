<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pengumuman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_has_many_pengumuman()
    {
        $admin = \App\Models\Admin::factory()->create();
        $pengumuman = $admin->pengumumans()->create([
            'admin_id' => $admin->admin_id,
            'title' => 'Test Announcement',
            'content' => 'Test Content',
            'status' => 'published',
            'post_date' => now(),
        ]);
        $admin->refresh();
        $admin->load('pengumumans');
        $this->assertTrue($admin->pengumumans->contains('post_id', $pengumuman->post_id));
    }

    public function test_pengumuman_belongs_to_admin()
    {
        $admin = \App\Models\Admin::factory()->create();
        $pengumuman = \App\Models\Pengumuman::factory()->create([
            'admin_id' => $admin->admin_id,
        ]);
        $this->assertNotNull($pengumuman->admin);
    }

    public function test_buku_belongs_to_kategori()
    {
        $kategori = \App\Models\Kategori::factory()->create();
        $buku = \App\Models\Buku::factory()->create([
            'category_id' => $kategori->category_id,
            'title' => 'Test Book',
            'author' => 'Test Author',
            'isbn' => '1234567890',
            'publication_year' => 2023,
            'publisher' => 'Test Publisher',
            'total_stock' => 1,
            'borrowed_qty' => 0,
            'available_qty' => 1,
        ]);
        $buku->refresh();
        $this->assertNotNull($buku->category, 'Buku should have an associated Kategori');
        $this->assertEquals($kategori->category_id, $buku->category->category_id);
    }

    public function test_member_has_many_wishlists()
    {
        $member = \App\Models\Member::factory()->create();
        $buku = \App\Models\Buku::factory()->create();
        $wishlist = $member->wishlists()->create([
            'book_id' => $buku->book_id,
            'added_date' => now(),
        ]);
        $member->refresh();
        $member->load('wishlists');
        $this->assertTrue($member->wishlists->contains('book_id', $wishlist->book_id));
    }

    public function test_bookmark_belongs_to_member_and_buku()
    {
        $member = \App\Models\Member::factory()->create();
        $buku = \App\Models\Buku::factory()->create();
        $bookmark = $member->bookmarks()->create([
            'book_id' => $buku->book_id,
            'created_at' => now(),
        ]);
        $this->assertEquals($member->member_id, $bookmark->member_id);
        $this->assertEquals($buku->book_id, $bookmark->book_id);
    }

    public function test_buku_has_many_reviews()
    {
        $buku = \App\Models\Buku::factory()->create();
        $member = \App\Models\Member::factory()->create();
        $review = $buku->reviews()->create([
            'member_id' => $member->member_id,
            'rating' => 5,
            'review_text' => 'Great book!',
            'created_at' => now(),
        ]);
        $buku->refresh();
        $buku->load('reviews');
        $this->assertTrue($buku->reviews->contains('id', $review->id));
    }

    public function test_member_has_many_bookmarks()
    {
        $member = \App\Models\Member::factory()->create();
        $buku = \App\Models\Buku::factory()->create();
        $bookmark = $member->bookmarks()->create([
            'book_id' => $buku->book_id,
            'created_at' => now(),
        ]);
        $member->refresh();
        $member->load('bookmarks');
        $this->assertTrue($member->bookmarks->contains('book_id', $bookmark->book_id));
    }

    public function test_member_has_many_reviews()
    {
        $member = \App\Models\Member::factory()->create();
        $buku = \App\Models\Buku::factory()->create();
        $review = $member->reviews()->create([
            'book_id' => $buku->book_id,
            'rating' => 4,
            'review_text' => 'Solid read.',
            'created_at' => now(),
        ]);
        $member->refresh();
        $member->load('reviews');
        $this->assertTrue($member->reviews->contains('id', $review->id));
    }

    public function test_buku_has_many_bookmarks()
    {
        $buku = \App\Models\Buku::factory()->create();
        $member = \App\Models\Member::factory()->create();
        $bookmark = $buku->bookmarks()->create([
            'member_id' => $member->member_id,
            'created_at' => now(),
        ]);
        $buku->refresh();
        $buku->load('bookmarks');
        $this->assertTrue($buku->bookmarks->contains('member_id', $bookmark->member_id));
    }

    public function test_buku_has_many_wishlists()
    {
        $buku = \App\Models\Buku::factory()->create();
        $member = \App\Models\Member::factory()->create();
        $wishlist = $buku->wishlists()->create([
            'member_id' => $member->member_id,
            'added_date' => now(),
        ]);
        $buku->refresh();
        $buku->load('wishlists');
        $this->assertTrue($buku->wishlists->contains('member_id', $wishlist->member_id));
    }

    public function test_buku_has_many_log_stock_buku()
    {
        $buku = \App\Models\Buku::factory()->create();
        $log = $buku->logStocks()->create([
            'entry_date' => now(),
            'qty_added' => 5,
            'qty_removed' => 0,
            'notes' => 'Restock',
        ]);
        $buku->refresh();
        $buku->load('logStocks');
        $this->assertTrue($buku->logStocks->contains('id', $log->id));
    }

    public function test_buku_has_many_log_pinjam_buku()
    {
        $buku = \App\Models\Buku::factory()->create();
        $member = \App\Models\Member::factory()->create();
        $log = $buku->logPinjams()->create([
            'member_id' => $member->member_id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'borrowed',
        ]);
        $buku->refresh();
        $buku->load('logPinjams');
        $this->assertTrue($buku->logPinjams->contains('id', $log->id));
    }

    public function test_member_has_many_log_pinjam_buku()
    {
        $member = \App\Models\Member::factory()->create();
        $buku = \App\Models\Buku::factory()->create();
        $log = $member->logPinjams()->create([
            'book_id' => $buku->book_id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'borrowed',
        ]);
        $member->refresh();
        $member->load('logPinjams');
        $this->assertTrue($member->logPinjams->contains('id', $log->id));
    }
}
