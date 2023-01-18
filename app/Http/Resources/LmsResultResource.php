<?php

namespace App\Http\Resources;

use App\Models\LmsResult;
use Illuminate\Http\Resources\Json\JsonResource;

class LmsResultResource extends JsonResource
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
            'uuid' => $this->uuid,
            'questions' => ResultQuestionResource::collection($this->questions),
            'questionOptions' => ResultOptionResource::collection($this->questionOptions),
            'quiz' => new ResultQuizResource($this->quiz),
            'user' => new UserResource($this->user),
            'time_spend' => $this->time_spend,
        ];
    }
}
