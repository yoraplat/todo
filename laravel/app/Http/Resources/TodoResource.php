<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'user_id'      => $this->user_id,
            'is_completed' => $this->is_completed,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
            'user'         => new UserResource($this->whenLoaded('user'))
        ];
    }
}
