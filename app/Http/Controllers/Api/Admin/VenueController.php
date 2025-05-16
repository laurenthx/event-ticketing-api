<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue; // Import Venue model
use App\Http\Resources\VenueResource; // Import your VenueResource
use App\Http\Requests\StoreVenueRequest; // Import StoreVenueRequest
use App\Http\Requests\UpdateVenueRequest; // Import UpdateVenueRequest
// Removed 'use Illuminate\Http\Request;' as it's not directly used when using FormRequests for POST/PUT

class VenueController extends Controller
{
    /**
     * Display a listing of the venues.
     * GET /api/admin/venues
     */
    public function index()
    {
        // Paginate results for better performance with large datasets
        $venues = Venue::orderBy('name')->paginate(15);
        return VenueResource::collection($venues);
    }

    /**
     * Store a newly created venue in storage.
     * POST /api/admin/venues
     */
    public function store(StoreVenueRequest $request) // Type-hint StoreVenueRequest for automatic validation
    {
        // If validation passes, $request->validated() contains only the validated data
        $venue = Venue::create($request->validated());
        return new VenueResource($venue); // Return the created venue using the resource (201 status auto-set by resource)
    }

    /**
     * Display the specified venue.
     * GET /api/admin/venues/{venue}
     */
    public function show(Venue $venue) // Route model binding automatically fetches the Venue
    {
        return new VenueResource($venue);
    }

    /**
     * Update the specified venue in storage.
     * PUT /api/admin/venues/{venue}
     */
    public function update(UpdateVenueRequest $request, Venue $venue) // Type-hint UpdateVenueRequest
    {
        $venue->update($request->validated());
        return new VenueResource($venue->fresh()); // Return the updated venue, 'fresh()' reloads from DB
    }

    /**
     * Remove the specified venue from storage.
     * DELETE /api/admin/venues/{venue}
     */
    public function destroy(Venue $venue)
    {
        // Prevent deleting a venue if it has associated events
        if ($venue->events()->exists()) { // Check if any events are linked to this venue
            return response()->json(['message' => 'Cannot delete venue with associated events. Please delete or reassign events first.'], 409); // 409 Conflict
        }

        $venue->delete();
        return response()->json(null, 204); // 204 No Content for successful deletion
    }
}