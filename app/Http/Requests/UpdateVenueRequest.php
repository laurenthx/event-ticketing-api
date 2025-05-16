<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Import Rule for unique validation on update

class UpdateVenueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization handled by 'admin' middleware and/or Policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $venueId = $this->route('venue')->id; // Get the current venue's ID from the route parameter

        return [
            // 'sometimes' means only validate if the field is present in the request
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('venues', 'name')->ignore($venueId), // Name must be unique, ignoring the current venue
            ],
            'location' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer|min:1',
        ];
    }
}