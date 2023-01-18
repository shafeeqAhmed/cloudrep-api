<?php

namespace App\Http\Resources;

use App\Models\LmsLesson;
use Illuminate\Http\Resources\Json\JsonResource;

class LmsQuizResource extends JsonResource
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
            'uuid' => $this->resource->lms_quiz_uuid,
            'id' => $this->resource->id,
            'lesson_id' => $this->resource->lesson_id,
            'lesson' => new LmsLessonResource($this->lesson),
            'name' => $this->resource->name,
            'is_active' => $this->resource->is_active,
            'duration' => !empty($this->duration) ? $this->duration->format('i:s') : null,
            'noOfQuestion' => $this->resource->no_of_question,
            'percentage' => $this->percentage,
            'required_points' => $this->percentage/10,
            'question_count' => count($this->questions),
            'questions' => LmsQuestionResource::collection($this->resource->questions)->shuffle(),
            'created_at' => $this->created_at,
        ];
    }
}
