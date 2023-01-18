<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'title' => [$rule, 'required', 'string'],
            'course_image' => [$rule, 'required', 'mimes:jpg,jpeg,png', 'max:5120'],
            'categories' =>  ['nullable', 'string'],
            'description' =>  ['nullable', 'string'],
            'tag_line' =>  ['nullable', 'string'],
            'price' =>  ['nullable', 'numeric','digits_between:0,10'],
        ];
    }
}
