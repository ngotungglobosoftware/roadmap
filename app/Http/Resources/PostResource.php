<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'user_id' => $this->authorId,
            'parentId' => $this->parentId,
            'title' => $this->title,
            'slug' => $this->slug,
            'metaTitle' => $this->metaTitle,
            'summary' => $this->summary,
            'content' => $this->content,
            'published' => $this->published,
            'publishedAt' => $this->publishedAt,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
