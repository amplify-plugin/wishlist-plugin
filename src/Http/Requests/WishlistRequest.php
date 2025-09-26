<?php

namespace Amplify\Wishlist\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WishlistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'customer_id' => 'required|integer|min:1',
            'contact_id' => 'required|integer|min:1',
            'product_id' => 'required|integer|min:1|unique:wishlists,product_id',
            'remove_from_cart' => 'nullable|boolean',
        ];

        if ($this->method() == 'PUT') {
            $rules['product_id'].= ','. $this->route('id');

        }

        return  $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
