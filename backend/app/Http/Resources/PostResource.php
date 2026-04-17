<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Post
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas(
                'id',
                $this->id
            ),
            'title' => $this->whenHas(
                'title',
                $this->title
            ),
            'content' => $this->whenHas(
                'content',
                $this->content
            ),
            'createdAt' => $this->whenHas(
                'created_at',
                Carbon::parse($this->created_at)
                    ->format('Y-m-d H:i:s')
            ),
            'updatedAt' => $this->whenHas(
                'updated_at',
                Carbon::parse($this->updated_at)
                    ->format('Y-m-d H:i:s')
            ),
            'authorUser' => $this->whenLoaded(
                'authorUser',
                function () {
                    return new UserResource($this->authorUser);
                }
            )
        ];
    }
}
