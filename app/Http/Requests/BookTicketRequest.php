<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Since this route will be protected by 'auth:api',
        // we can assume the user is authenticated.
        // Further authorization (e.g., can this user book for this event)
        // could be done via a Policy if needed, but not required by basic spec.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // Example: Allow booking 1 to 5 tickets at a time.
            'quantity' => 'sometimes|integer|min:1|max:5',
            // Seat info can be optional for general admission, or more complex if seating is assigned.
            // For this simple system, let's make it optional.
            'seat_info' => 'nullable|string|max:255',
        ];
    }
}