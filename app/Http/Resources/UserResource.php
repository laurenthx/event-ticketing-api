<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin, // Include is_admin status
            // 'when()' ensures 'preferred_categories' is only included if not null,
            // and defaults to an empty array if it is null.
            'preferred_categories' => $this->when($this->preferred_categories, $this->preferred_categories, []),
            'created_at' => $this->created_at->toDateTimeString(), // Format datetime
            'updated_at' => $this->updated_at->toDateTimeString(), // Format datetime
        ];
    }
}