<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        // If route('review') is available, it's an update, else create
        $isUpdate = $this->route('review') !== null;
        $rules = [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['required', 'string', 'max:1000'],
        ];
        if (!$isUpdate) {
            $rules['book_id'] = ['required', 'exists:buku,book_id'];
            $rules['member_id'] = ['required', 'exists:member,member_id'];
        }
        return $rules;
    }
}
