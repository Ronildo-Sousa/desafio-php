<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $admin = auth()->user()->is_admin;

        $rules = [
            'name'     => ['string', 'min:4'],
            'password' => ['string', 'min:8'],
        ];

        if ($admin) {
            $rules['is_admin'] = ['boolean'];
        }

        return $rules;
    }
}
