<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|string', // Adjust if using file upload
        ];
    }
}
