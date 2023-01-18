<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientProfileItemRequest extends FormRequest
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
        if (request()->isMethod('post')) {
            $rule = 'required';
        }


        if (request()->isMethod('put')) {
            $rule = 'sometimes';
        }
        return [
            'user_id' => [$rule,'required', 'integer']
        ];
    }
}
