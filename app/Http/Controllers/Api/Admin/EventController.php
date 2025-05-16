<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event; // Import Event model
use App\Http\Resources\EventResource; // Import your EventResource
use Illuminate\Http\Request; // Import Request for accessing query parameters

class EventController extends Controller
{
    /**
     * Display a listing of upcoming events.
     * Supports filtering by category, venue_id, and date range.
     * If user is authenticated and has preferred_categories, relevant events are shown first.
     * GET /api/events
     */
    public function index(Request $request)
    {
        // Start with upcoming events, eager load venue, and count tickets sold
        // withCount('tickets') will add a 'tickets_count' attribute to each event model
        $query = Event::upcoming()->with(['venue', 'creator'])->withCount('tickets');

        // Bonus: Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Bonus: Filter by venue_id
        if ($request->has('venue_id') && $request->venue_id) {
            $query->where('venue_id', (int)$request->venue_id);
        }

        // Bonus: Filter by date range (start_time)
        if ($request->has('date_from') && $request->date_from) {
            try {
                $dateFrom = \Carbon\Carbon::parse($request->date_from)->startOfDay();
                $query->where('start_time', '>=', $dateFrom);
            } catch (\Exception $e) {
                // Silently ignore invalid date_from format for this example
                // Or return a 400 error:
                // return response()->json(['message' => 'Invalid date_from format. Please use YYYY-MM-DD.'], 400);
            }
        }
        if ($request->has('date_to') && $request->date_to) {
             try {
                $dateTo = \Carbon\Carbon::parse($request->date_to)->endOfDay();
                $query->where('start_time', '<=', $dateTo);
            } catch (\Exception $e) {
                // Silently ignore invalid date_to format
            }
        }

        // Bonus: Sort by preferred categories if user is authenticated
        //         and has preferred_categories set (and it's an array).
        $user = $request->user('api'); // Get user from 'api' guard if authenticated
        if ($user && is_array($user->preferred_categories) && count($user->preferred_categories) > 0) {
            $preferred = $user->preferred_categories;
            // Sanitize preferred categories to prevent SQL injection if they were free text
            $sanitizedPreferred = [];
            foreach($preferred as $cat) {
                if (is_string($cat) && strlen($cat) < 100) { // Basic sanity check
                    $sanitizedPreferred[] = $cat;
                }
            }

            if (count($sanitizedPreferred) > 0) {
                $preferredPlaceholders = implode(',', array_fill(0, count($sanitizedPreferred), '?'));
                // Order by category being in preferred list (DESC means preferred first), then by start_time
                $query->orderByRaw("FIELD(category, {$preferredPlaceholders}) DESC", $sanitizedPreferred);
            }
        }

        // Always order by start time as a secondary sort or primary if no preferred categories
        $events = $query->orderBy('start_time', 'asc')->paginate(10); // Paginate results

        return EventResource::collection($events);
    }

    /**
     * Display the specified event.
     * GET /api/events/{event}
     */
    public function show(Event $event) // Route model binding
    {
        // Load necessary relationships for the single event response
        $event->load(['venue', 'creator'])->loadCount('tickets');
        return new EventResource($event);
    }

    /**
     * Bonus: Search endpoint to look up events by name or venue name.
     * GET /api/events/search
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2', // Search query 'q'
        ]);

        $searchTerm = $request->q;

        $events = Event::upcoming() // Only search upcoming events
            ->with(['venue', 'creator'])->withCount('tickets')
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('venue', function ($venueQuery) use ($searchTerm) {
                          $venueQuery->where('name', 'LIKE', "%{$searchTerm}%");
                      });
            })
            ->orderBy('start_time', 'asc')
            ->paginate(10);

        return EventResource::collection($events);
    }
}