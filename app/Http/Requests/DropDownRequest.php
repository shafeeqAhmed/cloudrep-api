<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DropDownRequest extends FormRequest
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
            'label' => [$rule,'required', 'string'],
            // 'value' => [$rule,'required', 'string'],
            'type' => [$rule,'required', 'string'],
        ];
    }
}
