<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignEnrollmentRequest extends FormRequest
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
        return [
            'campaign_id' => ['required', 'integer'],
            'client_id' => ['required', 'integer'],
            'publisher_id' => [ 'required', 'integer'],
            'publisher_DID' => ['required'],
             'start_date_time'=>['required|date_format:Y-m-d H:i'],
             'end_date_time'=>['required|date_format:Y-m-d H:i'],
             'publisher_timezone'=>['required|date_format:Y-m-d H:i'],
             'client_timezone'=>['required|date_format:Y-m-d H:i']
        ];
    }
}
