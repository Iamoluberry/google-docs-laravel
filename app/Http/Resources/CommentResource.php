<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "text" => $this->text,
            "userId" => $this->user_id,
            "postId" => $this->post_id,
            "createAt" => $this->created_at,
            "updatedAt" => $this->updated_at,
        ];
    }
}
