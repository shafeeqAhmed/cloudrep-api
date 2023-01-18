<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentCampaignRegistrationRequest extends FormRequest
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
        if(request()->isMethod('post')) {
            $rule = 'required';
        }

        if(request()->isMethod('put')) {
            $rule = 'sometimes';
        }

        return [
            'agent_username' => [$rule,'string','unique:agent_campaign_registrations,agent_username'],
            'agent_title' => ['nullable','string'],
            'agent_address' => ['nullable','string'],
            'status' => ['nullable','string'],
            'working_state' => ['nullable','string'],
            'open_time' => ['nullable'],
            'close_time' => ['nullable'],
            'time_zone' => ['nullable','string']
        ];
    }
}
