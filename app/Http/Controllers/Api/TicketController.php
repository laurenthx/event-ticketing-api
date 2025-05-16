<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Http\Resources\TicketResource;
use App\Http\Requests\BookTicketRequest;
use Illuminate\Http\Request; // <-- ADD THIS IMPORT if not already there
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\TicketConfirmationMail;

class TicketController extends Controller
{
    /**
     * Display a listing of the authenticated user's booked tickets.
     * Tickets are sorted by the event's start_time (upcoming first).
     * GET /api/my-tickets
     */
    public function index(Request $request) // Add Request for potential future use
    {
        $user = Auth::user(); // Get the currently authenticated user

        // Fetch tickets for the user
        // Eager load 'event' and 'event.venue' to prevent N+1 query issues
        // Join with the 'events' table to sort by 'events.start_time'
        $tickets = $user->tickets() // Start with the user's tickets relationship
                        ->with(['event.venue']) // Eager load event and the event's venue
                        ->join('events', 'tickets.event_id', '=', 'events.id') // Join to access event's start_time
                        ->orderBy('events.start_time', 'asc') // Sort by event's start date, upcoming first
                        ->select('tickets.*') // Important: Select only columns from the 'tickets' table to avoid ambiguity
                        ->paginate(10); // Paginate the results

        return TicketResource::collection($tickets);
    }

    /**
     * Store a newly created ticket booking in storage.
     * POST /api/events/{event}/tickets
     */
    public function store(BookTicketRequest $request, Event $event)
    {
        // ... (your existing store method code for booking tickets) ...
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);
        $seatInfo = $request->input('seat_info', 'General Admission');

        if ($event->start_time < now()) {
            return response()->json(['message' => 'Cannot book tickets for past events.'], 400);
        }

        try {
            $createdTickets = DB::transaction(function () use ($event, $user, $quantity, $seatInfo) {
                $eventLocked = Event::with('venue')->withCount('tickets')->lockForUpdate()->findOrFail($event->id);
                $venueCapacity = $eventLocked->venue->capacity;
                $ticketsSold = $eventLocked->tickets_count;

                if (($ticketsSold + $quantity) > $venueCapacity) {
                    throw new \Exception('Not enough available seats for this event.');
                }

                $tickets = [];
                for ($i = 0; $i < $quantity; $i++) {
                    $tickets[] = Ticket::create([
                        'user_id' => $user->id,
                        'event_id' => $eventLocked->id,
                        'price' => $eventLocked->price,
                        'seat_info' => $seatInfo . ($quantity > 1 ? " - Ticket " . ($i + 1) : ""),
                        'booking_time' => now(),
                    ]);
                }
                return $tickets;
            }, 3);

            if (!empty($createdTickets)) {
                Log::info("Tickets booked for user {$user->email} for event '{$event->title}'. Quantity: {$quantity}. First Ticket ID: {$createdTickets[0]->id}");
            }

            return TicketResource::collection(collect($createdTickets))
                                 ->response()
                                 ->setStatusCode(201);

        } catch (\Exception $e) {
            if ($e->getMessage() === 'Not enough available seats for this event.') {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            Log::error('Ticket booking failed: ' . $e->getMessage(), ['user_id' => $user->id, 'event_id' => $event->id]);
            return response()->json(['message' => 'Ticket booking failed. Please try again later.'], 500);
        }
    }
}