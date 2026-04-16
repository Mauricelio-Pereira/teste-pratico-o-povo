<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\User
 */
class UserResource extends JsonResource
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
            'name' => $this->whenHas(
                'name',
                $this->name
            ),
            'email' => $this->whenHas(
                'email',
                $this->email
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
        ];
    }
}
