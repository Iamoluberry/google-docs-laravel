<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = 'post';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->body,
            "createAt" => $this->created_at,
            "updatedAt" => $this->updated_at,
            "user" => $this->when(
                $request->routeIs('post.show'),
                $this->users->map(function ($user){
                    return [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                    ];
                }),
            )
        ];
    }
}
