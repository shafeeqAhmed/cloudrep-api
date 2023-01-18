<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;

class UserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if (request()->isMethod('post')) {
            $rule = 'required';
        }

        if (request()->isMethod('put')) {
            $rule = 'sometimes';
        }
        return [
            'first_name' => [ $rule,'required', 'string', 'max:10'],
            'last_name' => [ $rule,'required', 'string', 'max:10'],
            'email' => [ $rule,'required', 'string', 'email', 'max:255', 'unique:users,email,'. $request->user()->id],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
            // 'password' => $this->passwordRules(),
            'role' => [
                // 'required',
                Rule::in(['client', 'agent', 'publisher'])
            ],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ];
    }
}
