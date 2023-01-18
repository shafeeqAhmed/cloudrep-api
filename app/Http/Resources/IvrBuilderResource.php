<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IvrBuilderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->resource->uuid,
            'parent_id' => $this->resource->parent_id,
            'node_type' => $this->resource->node_type,
            'time_zone' => $this->resource->time_zone,
            'tag_name' => $this->resource->tag_name,
            'timeout' => $this->resource->timeout,
            'no_of_retries' => $this->resource->no_of_retries,
            'no_of_reproduce' => $this->resource->no_of_reproduce,
            'node_content_type' => $this->resource->node_content_type,
            'sound' => $this->resource->sound,
            'text' => $this->resource->text,
            'text_voice' => $this->resource->text_voice,
            'text_language' => $this->resource->text_language,
            'pixel_url' => $this->resource->pixel_url,
            'pixel_max_fires' => $this->resource->pixel_max_fires,
            'pixel_advanced' => $this->resource->pixel_advanced,
            'pixel_method' => $this->resource->pixel_method,
            'pixel_content_type' => $this->resource->pixel_content_type,
            'pixel_body' => $this->resource->pixel_body,
            'pixel_custom_header_1' => $this->resource->pixel_custom_header_1,
            'pixel_custom_header_2' => $this->resource->pixel_custom_header_2,
            'pixel_authorization' => $this->resource->pixel_authorization,
            'pixel_username' => $this->resource->pixel_username,
            'pixel_password' => $this->resource->pixel_password,
            'open_time' => $this->resource->open_time,
            'close_time' => $this->resource->close_time,
            'start_break_time' => $this->resource->start_break_time,
            'break_duration' => $this->resource->break_duration,
            'dial_recording_setting' => $this->resource->dial_recording_setting,
            'dial_max_call_length' => $this->resource->dial_max_call_length,
            'dial_max_recording_time' => $this->resource->dial_max_recording_time,
            'dial_caller_id' => $this->resource->dial_caller_id,
            'dial_wishper' => $this->resource->dial_wishper,
            'dial_routing_plan' => $this->resource->dial_routing_plan,
            'gather_max_number_of_digits' => $this->resource->gather_max_number_of_digits,
            'gather_min_number_of_digits' => $this->resource->gather_min_number_of_digits,
            'gather_valid_digits' => $this->resource->gather_valid_digits,
            'gather_finish_on_key' => $this->resource->gather_finish_on_key,
            'gather_key_press_timeout' => $this->resource->gather_key_press_timeout,
            'hangup_message' => $this->resource->hangup_message,
            'voicemail_max_length' => $this->resource->voicemail_max_length,
            'voicemail_finish_on_key' => $this->resource->voicemail_finish_on_key,
            'voicemail_play_beep' => $this->resource->voicemail_play_beep,
            'voicemail_email_notification' => $this->resource->voicemail_email_notification,
            'voicemail_message' => $this->resource->voicemail_message,
            'on_failer' => $this->resource->on_failer,
            'on_success' => $this->resource->on_success,
            'press_0' => $this->resource->press_0,
            'press_1' => $this->resource->press_1,
            'press_2' => $this->resource->press_2,
            'press_3' => $this->resource->press_3,
            'press_4' => $this->resource->press_4,
            'press_5' => $this->resource->press_5,
            'press_6' => $this->resource->press_6,
            'press_7' => $this->resource->press_7,
            'press_8' => $this->resource->press_8,
            'press_9' => $this->resource->press_9,
            'created_at' => $this->resource->created_at
        ];
    }
}
