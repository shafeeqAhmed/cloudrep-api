<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LmsLessonResource extends JsonResource
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
            'uuid' => $this->resource->lms_lesson_uuid,
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'is_active' => $this->resource->is_active,
            'course_id' => $this->resource->course_id,
            'videos' => LmsLessonVideoResource::collection($this->resource->videos),
            'quizes' => LmsQuizResource::collection($this->resource->quizes),
            'created_at' => $this->created_at,
        ];
    }
}
