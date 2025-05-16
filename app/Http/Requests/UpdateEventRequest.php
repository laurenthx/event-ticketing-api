<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Venue;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorization via admin middleware
    }

    public function rules()
    {
        return [
            // 'sometimes' means only validate if present in the request
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_time' => 'sometimes|required|date', // For update, 'after_or_equal:now' might be too restrictive if editing past event details
            'end_time' => 'sometimes|required|date|after:start_time',
            'venue_id' => ['sometimes', 'required', 'integer', Rule::exists(Venue::class, 'id')],
            'category' => 'sometimes|nullable|string|max:100',
            'price' => 'sometimes|required|numeric|min:0',
        ];
    }
}