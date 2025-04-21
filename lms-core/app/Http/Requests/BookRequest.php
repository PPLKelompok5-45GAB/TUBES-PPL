<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
     * @return array<string, array<int|string, string>|string>
     */
    public function rules(): array
    {
        $bookRoute = $this->route('book');
        $bookId = (is_object($bookRoute) && property_exists($bookRoute, 'buku_id')) ? $bookRoute->buku_id : null;
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('buku', 'isbn')->ignore($bookId, 'buku_id'),
            ],
            'category_id' => 'required|exists:kategori,category_id',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ];
    }
}
