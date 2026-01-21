<?php

namespace Amplify\Wishlist\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WishlistNotificationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notify' => ['required', 'boolean'],
            'product_id' => ['integer', 'required'],
        ];
    }
}
