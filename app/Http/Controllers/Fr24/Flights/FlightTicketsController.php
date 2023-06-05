<?php

namespace App\Http\Controllers\Fr24\Flights;

use App\Models\Fr24\Flight;
use App\Models\Fr24\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Fr24\TicketResource;

class FlightTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Flight $flight)
    {

        if(!$flight->isOwnedByCurrentUser())
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to view this resource'
            ], 401);

        return TicketResource::collection($flight->tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Flight $flight, Request $request)
    {

        // Define accepted POST params
        $acceptedParameters = ['passport_ref_no', 'seat'];

        // Make sure current user owns this flight
        if(!$flight->isOwnedByCurrentUser())
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to view this resource'
            ], 401);

        // Validate request
        $request->validate([
            'passport_ref_no' => 'required|min:8|max:60|alpha_num',
            'seat' => 'integer|min:1|max:' . $flight->available_seats
        ]);

        // Fetch booked seats
        $bookedFlightSeats = $flight->tickets()->booked()->get()->pluck('seat');

        // Make sure flight isn't fully booked
        if($bookedFlightSeats->count() >= $flight->available_seats)
            return response()->json([
                'status' => 'error',
                'message' => 'Flight is fully booked'
            ], 422);

        // No desired seat, let's assign a random one of the ones remaining
        if(!$request->get('seat')) {
            
            // Assign a random seat out of the available seats left
            $seat = collect(range(1, $flight->available_seats))
                    ->diff($bookedFlightSeats->toArray())
                    ->flatten()
                    ->random();
        
        } else {
            
            // Desired seat, make sure the seat isn't booked
            if($bookedFlightSeats->contains((int) $request->get('seat'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'That seat is already booked'
                ], 422);
            }

            // All good
            $seat = (int) $request->get('seat');

        }

        // Store ticket
        $ticket = Ticket::create(
            array_merge($request->only($acceptedParameters), [
                'flight_id' => $flight->id,
                'seat' => $seat
            ])
        );

        return response()->json([
            'status' => 'success',
            'ticket' => Ticket::with('flight')->findOrFail($ticket->id)
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
