<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->content,
            'image_url' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'status' => $this->status?->value ?? $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'view_count' => $this->view_count,
            'is_featured' => $this->is_featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => [
                'name' => $this->user?->name,
                'avatar_url' => $this->user?->getFilamentAvatarUrl(),
            ],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
