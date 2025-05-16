<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVenueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization will be handled by the 'admin' middleware on the route
        // and potentially a Policy later if more granular control is needed.
        // For now, if the request reaches this FormRequest, we assume authorization
        // via middleware has passed.
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
            'name' => 'required|string|max:255|unique:venues,name', // Venue name must be unique
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1', // Capacity must be at least 1
        ];
    }
}