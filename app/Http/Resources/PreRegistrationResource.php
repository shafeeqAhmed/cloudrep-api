<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PreRegistrationResource extends JsonResource
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
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'business_scale_type' => $this->resource->business_scale_type,
            'work_type_with_cloudrep' => $this->resource->work_type_with_cloudrep,
            'phone_no' => $this->resource->phone_no,
            'is_verified' => $this->resource->is_verified,
            'business_name' => $this->resource->business_name,
            'business_category' => $this->resource->businessCategory ? new BussinesCategoryResource($this->resource->businessCategory) : '' ,
        ];
    }
}
