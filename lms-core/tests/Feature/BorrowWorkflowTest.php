<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Log_Pinjam_Buku;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_request_borrow_valid()
    {
        $memberUser = User::factory()->create(['role' => 'Member']);
        $member = Member::factory()->create();
        $book = Buku::factory()->create(['available_qty' => 2, 'borrowed_qty' => 0, 'total_stock' => 2]);
        $this->actingAs($memberUser);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'borrow_date' => now()->toDateString(),
        ];
        $response = $this->post('/borrow', $data);
        $response->assertRedirect('/borrow');
        $response->assertSessionHas('status', 'Borrow request submitted and pending approval.');
        
        $this->assertDatabaseHas('log_pinjam_buku', [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'status' => 'pending',
            'due_date' => null, // Due date should be null until approved
        ]);
        
        // Book stock should remain unchanged until approval
        $book->refresh();
        $this->assertEquals(2, $book->available_qty);
        $this->assertEquals(0, $book->borrowed_qty);
    }

    public function test_member_cannot_request_borrow_invalid()
    {
        $user = User::factory()->create(['role' => 'Member']);
        $member = Member::factory()->create();
        $book = Buku::factory()->create(['available_qty' => 0, 'borrowed_qty' => 1, 'total_stock' => 1]);
        $this->actingAs($user);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'borrow_date' => now()->toDateString(),
        ];
        $response = $this->post('/borrow', $data);
        $response->assertSessionHasErrors('book_id');
        
        // Verify no borrow record was created
        $this->assertDatabaseMissing('log_pinjam_buku', [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'borrow_date' => now()->toDateString(),
        ]);
    }

    public function test_admin_can_approve_borrow()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $book = Buku::factory()->create([
            'available_qty' => 3, 
            'borrowed_qty' => 0,
            'total_stock' => 3
        ]);
        $borrow = Log_Pinjam_Buku::factory()->create([
            'status' => 'pending',
            'book_id' => $book->book_id,
        ]);
        
        $this->actingAs($admin);
        $response = $this->post('/borrow/' . $borrow->loan_id . '/approve');
        
        $response->assertRedirect('/borrow');
        $response->assertSessionHas('status', 'Borrow request approved.');
        
        // Check borrow record was updated correctly
        $borrow->refresh();
        $this->assertEquals('approved', $borrow->status);
        $this->assertNotNull($borrow->due_date);
        
        // Check book stock was updated correctly
        $book->refresh();
        $this->assertEquals(2, $book->available_qty, 'Available quantity should decrease by 1');
        $this->assertEquals(1, $book->borrowed_qty, 'Borrowed quantity should increase by 1');
        $this->assertEquals(3, $book->total_stock, 'Total stock should remain unchanged');
    }

    public function test_admin_can_reject_borrow()
    {
        $admin = \App\Models\User::factory()->create(['role' => 'Admin']);
        $borrow = \App\Models\Log_Pinjam_Buku::factory()->create(['status' => 'pending']);
        $this->actingAs($admin);
        $response = $this->post('/borrow/' . $borrow->loan_id . '/reject');
        $response->assertRedirect('/borrow');
        $this->assertDatabaseHas('log_pinjam_buku', [
            'loan_id' => $borrow->loan_id,
            'status' => 'rejected',
        ]);
    }

    public function test_return_processing_updates_status_and_stock()
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $book = Buku::factory()->create([
            'available_qty' => 2, 
            'borrowed_qty' => 1,
            'total_stock' => 3
        ]);
        
        $borrow = Log_Pinjam_Buku::factory()->create([
            'status' => 'approved',
            'book_id' => $book->book_id,
            'due_date' => now()->addDays(7)->toDateString(),
            'return_date' => null,
        ]);
        
        $this->actingAs($admin);
        $response = $this->post('/borrow/' . $borrow->loan_id . '/return');
        
        $response->assertRedirect('/borrow');
        $response->assertSessionHas('status', 'Book returned successfully.');
        
        // Check borrow record was updated correctly
        $borrow->refresh();
        $this->assertEquals('returned', $borrow->status);
        $this->assertNotNull($borrow->return_date);
        
        // Check book stock was updated correctly
        $book->refresh();
        $this->assertEquals(3, $book->available_qty, 'Available quantity should increase by 1');
        $this->assertEquals(0, $book->borrowed_qty, 'Borrowed quantity should decrease by 1');
        $this->assertEquals(3, $book->total_stock, 'Total stock should remain unchanged');
    }

    public function test_overdue_detection_works_correctly()
    {
        // Setup a book
        $book = Buku::factory()->create(['available_qty' => 0, 'borrowed_qty' => 1, 'total_stock' => 1]);
        
        // Create an approved borrow that is now overdue
        $borrow = Log_Pinjam_Buku::factory()->create([
            'status' => 'approved',
            'book_id' => $book->book_id,
            'due_date' => now()->subDays(3)->toDateString(), // 3 days overdue
            'return_date' => null,
            'overdue_count' => 0,
        ]);
        
        // Create a controller instance and run the overdue check
        $controller = new \App\Http\Controllers\BorrowController();
        $count = $controller->checkOverdueBorrows();
        
        // Assert that one borrow was found and updated
        $this->assertEquals(1, $count, 'One overdue borrow should be detected');
        
        // Verify the borrow status was updated
        $borrow->refresh();
        $this->assertEquals('overdue', $borrow->status, 'Status should be changed to overdue');
        $this->assertEquals(1, $borrow->overdue_count, 'Overdue count should be incremented');
        
        // Book stock should remain unchanged when marking as overdue
        $book->refresh();
        $this->assertEquals(0, $book->available_qty);
        $this->assertEquals(1, $book->borrowed_qty);
    }
    
    public function test_member_cannot_submit_duplicate_borrow_requests()
    {
        $memberUser = User::factory()->create(['role' => 'Member']);
        $member = Member::factory()->create();
        $book = Buku::factory()->create(['available_qty' => 1, 'borrowed_qty' => 0, 'total_stock' => 1]);
        
        // Create an existing pending request
        Log_Pinjam_Buku::factory()->create([
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'status' => 'pending',
        ]);
        
        // Try to create another request for the same book/member
        $this->actingAs($memberUser);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'borrow_date' => now()->toDateString(),
        ];
        
        $response = $this->post('/borrow', $data);
        $response->assertSessionHasErrors('book_id');
        
        // Verify only one borrow record exists
        $count = Log_Pinjam_Buku::where('member_id', $member->member_id)
                              ->where('book_id', $book->book_id)
                              ->where('status', 'pending')
                              ->count();
        $this->assertEquals(1, $count, 'Only one pending request should exist');
    }
}
