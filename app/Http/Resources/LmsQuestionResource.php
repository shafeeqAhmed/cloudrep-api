<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LmsQuestionResource extends JsonResource
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
            'questionOptions' => LmsQuestionOptionResource::collection($this->resource->questionOptions)->shuffle(),
            // 'result' => new LmsResultResource($this->result),
            'created_at' => date('m/d/Y', strtotime($this->created_at)),
        ];
    }
}
