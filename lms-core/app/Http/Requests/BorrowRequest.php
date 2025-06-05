<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'book_id' => [
                'required', 
                'exists:buku,book_id',
                function ($attribute, $value, $fail) {
                    // Check if book is available
                    $book = \App\Models\Buku::find($value);
                    if ($book && $book->available_qty <= 0) {
                        $fail('This book is currently unavailable for borrowing.');
                    }
                    
                    // Check if member already has an active borrow for this book
                    $memberId = $this->input('member_id');
                    $existingBorrow = \App\Models\Log_Pinjam_Buku::where('book_id', $value)
                        ->where('member_id', $memberId)
                        ->whereIn('status', ['pending', 'approved', 'overdue'])
                        ->exists();
                        
                    if ($existingBorrow) {
                        $fail('You already have an active borrow request or loan for this book.');
                    }
                },
            ],
            'member_id' => ['required', 'exists:member,member_id'],
            'borrow_date' => ['required', 'date'],
        ];
    }
}
