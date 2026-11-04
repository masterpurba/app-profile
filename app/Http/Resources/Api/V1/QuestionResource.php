<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'body' => $this->body,
            'name' => $this->name,
            'status' => $this->status?->value ?? $this->status,
            'topic' => new QuestionTopicResource($this->whenLoaded('topic')),
            'answers' => QuestionAnswerResource::collection(
                $this->whenLoaded('answers', fn () => $this->answers->where('is_published', true))
            ),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
