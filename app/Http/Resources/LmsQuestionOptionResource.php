<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LmsQuestionOptionResource extends JsonResource
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
            'uuid' => $this->resource->lms_question_options_uuid,
            'name' => $this->resource->name,
            'is_active' => $this->resource->is_active,
            'is_true' => $this->resource->is_true,
            'question_id' => $this->resource->question_id,
            // 'quiz' => new LmsQuizResource($this->resource->quiz),
            'created_at' => $this->created_at,
        ];
    }
}
