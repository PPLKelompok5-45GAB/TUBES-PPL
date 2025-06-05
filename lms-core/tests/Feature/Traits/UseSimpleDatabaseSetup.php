<?php

namespace Tests\Feature\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Member;
use App\Models\Buku;
use App\Models\Bookmark;
use App\Models\Wishlist;

trait UseSimpleDatabaseSetup
{
    /**
     * Create a test user for our simplified test database
     * 
     * @param array $attributes Override default attributes
     * @return User
     */
    protected function createTestUser(array $attributes = []): User
    {
        $defaultAttributes = [
            'username' => 'testuser',
            'firstname' => 'Test',
            'lastname' => 'User',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'address' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'postal' => '12345',
            'about' => 'This is a test user account',
            'role' => 'Member',
            'email_verified_at' => now(),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ];
        
        // Make sure we have a unique email if not provided
        if (!isset($attributes['email'])) {
            $attributes['email'] = 'test' . time() . rand(1000, 9999) . '@example.com';
            $attributes['username'] = 'user' . time() . rand(100, 999);
        }
        
        $userData = array_merge($defaultAttributes, $attributes);
        
        return \App\Models\User::create($userData);
    }
    
    /**
     * Create a test member for our simplified test database
     * 
     * @param \App\Models\User|null $user User to associate with the member
     * @param array $attributes Override default member attributes
     * @return \App\Models\Member
     */
    protected function createTestMember($user = null, array $attributes = []): \App\Models\Member
    {
        if (!$user) {
            $user = $this->createTestUser();
        }
        
        $defaultAttributes = [
            'name' => $user->firstname . ' ' . $user->lastname,
            'email' => $user->email, // This is the field that connects to the User model
            'status' => 'active',
            'membership_date' => now()->format('Y-m-d'),
            'phone' => '123-456-7890',
            'address' => $user->address ?? '123 Test Street',
        ];
        
        $memberData = array_merge($defaultAttributes, $attributes);
        
        return \App\Models\Member::create($memberData);
    }
    
    /**
     * Create a test book for our simplified test database
     * 
     * @param array $attributes Override default book attributes
     * @return \App\Models\Buku
     */
    protected function createTestBook(array $attributes = []): \App\Models\Buku
    {
        $defaultAttributes = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'isbn' => '1234567890',
            'available_qty' => 1,
            'borrowed_qty' => 0,
        ];
        
        $bookData = array_merge($defaultAttributes, $attributes);
        
        return \App\Models\Buku::create($bookData);
    }
    /**
     * Create a simple empty paginator for test views that need pagination.
     * Instead of mocking, we're going to override the view with a simplified version.
     * 
     * @param string $modelClass The fully qualified class name of the model to handle
     * @return void
     */
    protected function mockPagination(string $modelClass): void
    {
        // Get any existing items for this model
        $items = $modelClass::all();
        
        // Create a paginator with those items
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,          // Items
            $items->count(), // Total count
            10,              // Per page
            1                // Current page
        );
        
        // Configure the paginator so links() won't fail
        $paginator->setPath('/');
        
        // Bind a view composer to inject our paginator whenever the view is rendered
        \Illuminate\Support\Facades\View::composer('vendor.argon.wishlists.index', function ($view) use ($paginator) {
            $view->with('wishlists', $paginator);
        });
    }
    
    /**
     * Set up a simple SQLite in-memory database for testing
     * 
     * @return void
     */
    protected function setupSimpleDatabase(): void
    {
        // Disable foreign key checks for SQLite
        DB::statement('PRAGMA foreign_keys = OFF');
        
        // Create simple schemas for testing without complex migrations
        $this->createUsersTable();
        $this->createMemberTable();
        $this->createBukuTable();
        $this->createBookmarksTable();
        $this->createWishlistTable();
        $this->createLogPinjamBukuTable();
        
        // Re-enable foreign key checks after setup
        DB::statement('PRAGMA foreign_keys = ON');
    }
    
    /**
     * Create a simplified log_pinjam_buku table for testing.
     */
    protected function createLogPinjamBukuTable(): void
    {
        Schema::create('log_pinjam_buku', function ($table) {
            $table->increments('log_id');
            $table->integer('member_id');
            $table->integer('book_id');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['borrowed', 'returned', 'overdue'])->default('borrowed');
            $table->timestamps();
        });
    }
    
    /**
     * Create a simple users table for testing
     */
    protected function createUsersTable(): void
    {
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('name')->nullable(); // Keeping this for backwards compatibility
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal')->nullable();
            $table->text('about')->nullable();
            $table->string('role')->default('Member');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    
    /**
     * Create a simple member table for testing
     */
    protected function createMemberTable(): void
    {
        Schema::create('member', function ($table) {
            $table->increments('member_id');
            $table->string('name');
            $table->string('email')->unique(); // Email is the link to User model
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('membership_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Create a simple buku (books) table for testing
     */
    protected function createBukuTable(): void
    {
        Schema::create('buku', function ($table) {
            $table->increments('book_id');
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->integer('available_qty')->default(1);
            $table->integer('borrowed_qty')->default(0);
            $table->timestamps();
        });
    }
    
    /**
     * Create a simple bookmarks table for testing
     */
    protected function createBookmarksTable(): void
    {
        Schema::create('bookmarks', function ($table) {
            $table->increments('bookmark_id');
            $table->integer('book_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->integer('page_number')->nullable();
            $table->text('notes')->nullable();
            $table->date('added_date')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Create a simple wishlist table for testing
     */
    protected function createWishlistTable(): void
    {
        Schema::create('wishlist', function ($table) {
            $table->increments('wishlist_id');
            $table->integer('book_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->date('added_date')->nullable();
            $table->timestamps();
        });
    }
}
