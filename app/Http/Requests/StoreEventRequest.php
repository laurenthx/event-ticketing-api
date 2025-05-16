<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import Rule
use App\Models\Venue; // Import Venue model for exists rule

class StoreEventRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorization via admin middleware
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after_or_equal:now', // Event must start now or in the future
            'end_time' => 'required|date|after:start_time', // End time must be after start time
            'venue_id' => ['required', 'integer', Rule::exists(Venue::class, 'id')], // Must be an existing venue ID
            'category' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0', // Price must be a number and not negative
        ];
    }
}