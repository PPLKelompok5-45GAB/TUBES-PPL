<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
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
        $memberRoute = $this->route('member');
        $memberId = (is_object($memberRoute) && property_exists($memberRoute, 'member_id')) ? $memberRoute->member_id : null;
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('member', 'email')->ignore($memberId, 'member_id'),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,suspended,inactive'],
            'membership_date' => ['required', 'date'],
        ];
    }
}
