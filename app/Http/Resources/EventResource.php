<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Calculate available seats if venue and tickets_count are loaded
        $availableSeats = null;
        if ($this->relationLoaded('venue') && $this->resource->relationLoaded('tickets') && isset($this->tickets_count)) {
             // The line above was slightly off, corrected below using venue from resource
        }
        if ($this->whenLoaded('venue') && isset($this->tickets_count)) {
             // Accessing venue capacity directly if the venue relationship is loaded.
             // tickets_count is typically loaded via withCount('tickets') on the Event query.
             $availableSeats = $this->venue->capacity - $this->tickets_count;
        }


        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time->toDateTimeString(),
            'end_time' => $this->end_time->toDateTimeString(),
            'category' => $this->category,
            'price' => (float) $this->price, // Cast to float for consistent JSON number type
            // Conditionally load related resources if they were eager-loaded with the Event model
            'venue' => new VenueResource($this->whenLoaded('venue')),
            'creator' => new UserResource($this->whenLoaded('creator')), // We created UserResource earlier
            // 'tickets_sold' will be present if you query events with withCount('tickets')
            'tickets_sold' => $this->when(isset($this->tickets_count), $this->tickets_count),
            'available_seats' => $availableSeats,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}