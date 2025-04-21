<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_request_borrow_valid()
    {
        $memberUser = \App\Models\User::factory()->create(['role' => 'Member']);
        $member = \App\Models\Member::factory()->create();
        $book = \App\Models\Buku::factory()->create(['available_qty' => 1]);
        $this->actingAs($memberUser);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(7)->toDateString(),
        ];
        $response = $this->post('/borrow', $data);
        $response->assertRedirect('/borrow');
        $this->assertDatabaseHas('log_pinjam_buku', [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
        ]);
    }

    public function test_member_cannot_request_borrow_invalid()
    {
        $user = \App\Models\User::factory()->create(['role' => 'Member']);
        $member = \App\Models\Member::factory()->create();
        $book = \App\Models\Buku::factory()->create(['available_qty' => 0]);
        $this->actingAs($user);
        $data = [
            'member_id' => $member->member_id,
            'book_id' => $book->book_id,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(7)->toDateString(),
        ];
        $response = $this->post('/borrow', $data);
        $response->assertSessionHasErrors('book_id');
    }

    public function test_admin_can_approve_borrow()
    {
        $admin = \App\Models\User::factory()->create(['role' => 'Admin']);
        $borrow = \App\Models\Log_Pinjam_Buku::factory()->create(['status' => 'pending']);
        $this->actingAs($admin);
        $response = $this->post('/borrow/' . $borrow->loan_id . '/approve');
        $response->assertRedirect('/borrow');
        $this->assertDatabaseHas('log_pinjam_buku', [
            'loan_id' => $borrow->loan_id,
            'status' => 'approved',
        ]);
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

    public function test_return_processing_updates_status_and_overdue()
    {
        $user = \App\Models\User::factory()->create(['role' => 'Member']);
        $borrow = \App\Models\Log_Pinjam_Buku::factory()->create([
            'status' => 'approved',
            'due_date' => now()->subDays(2)->toDateString(),
            'return_date' => null,
            'overdue_count' => 0,
        ]);
        $this->actingAs($user);
        $response = $this->post('/borrow/' . $borrow->loan_id . '/return');
        $response->assertRedirect('/borrow');
        $this->assertDatabaseHas('log_pinjam_buku', [
            'loan_id' => $borrow->loan_id,
            'status' => 'returned',
            'overdue_count' => 1,
        ]);
    }
}
