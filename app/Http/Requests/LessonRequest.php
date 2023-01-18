<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            'name' => [$rule,'required', 'string'],
            'course_uuid' => [$rule,'required','uuid'],
            // 'video' => 'mimes:mp4,ogx,oga,ogv,ogg,webm'
            // 'videos' => 'video/avi,video/mpeg,video/quicktime,size:20'
        ];
    }
}
