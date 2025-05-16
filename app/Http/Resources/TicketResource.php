<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'price' => (float) $this->price, // Cast to float
            'seat_info' => $this->seat_info,
            'booking_time' => $this->booking_time->toDateTimeString(),
            // Conditionally load related user and event if they were eager-loaded
            'user' => new UserResource($this->whenLoaded('user')),
            'event' => new EventResource($this->whenLoaded('event')),
            'created_at' => $this->created_at->toDateTimeString(), // Tickets also have created_at/updated_at
            // 'updated_at' => $this->updated_at->toDateTimeString(), // Usually not needed for tickets in API response
        ];
    }
}