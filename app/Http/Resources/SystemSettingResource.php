<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemSettingResource extends JsonResource
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
            'setting_uuid' => $this->resource->setting_uuid,
            'name' => $this->resource->name,
            'value' => $this->resource->value,
            'created_at' => $this->created_at,
        ];
    }
}
