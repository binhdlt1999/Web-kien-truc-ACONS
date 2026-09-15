<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+().\s-]{8,30}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'message' => ['nullable', 'string', 'max:3000'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Số điện thoại không đúng định dạng.',
            'website.max' => 'Không thể gửi biểu mẫu này.',
        ];
    }
}
