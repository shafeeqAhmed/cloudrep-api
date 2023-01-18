<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultQuestionResource extends JsonResource
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
            'uuid' => $this->resource->lms_question_uuid,
            'name' => $this->resource->name,
            'is_active' => $this->resource->is_active,
            'created_at' => date('m/d/Y', strtotime($this->created_at)),
        ];
    }
}
