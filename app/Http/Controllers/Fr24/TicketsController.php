<?php

namespace App\Http\Controllers\Fr24;

use App\Models\Fr24\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        // Fetch ticket with associated flight
        $ticket = Ticket::with('flight')->findOrFail($id);

        // Make sure current user owns this flight
        if(!$ticket->flight->isOwnedByCurrentUser())
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to view this resource'
            ], 401);

        return $ticket;
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {

        // Fetch ticket with associated flight
        $ticket = Ticket::with('flight')->findOrFail($id);

        // Make sure current user owns this flight
        if(!$ticket->flight->isOwnedByCurrentUser())
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to handle this resource'
            ], 401);

        // Ticket is cancelled
        if($ticket->cancelled_at)
            return response()->json([
                'status' => 'error',
                'message' => 'This ticket has been cancelled'
            ], 422);

        // Validate request
        $request->validate([
            'passport_ref_no' => 'min:8|max:60|alpha_num',
            'seat' => 'integer|min:1|max:' . $ticket->flight->available_seats
        ]);

        // Updating seat, let's make sure seat is available
        if($request->get('seat')) {
            $occupiedSeat = Ticket::where('seat', (int) $request->get('seat'))
                                  ->where('flight_id', $ticket->flight->id)->get();

            // Seat occupied
            if(!$occupiedSeat->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Seat ' . $request->get('seat') . ' is already occupied'
                ], 422);
            }
        }

        // Update ticket
        $ticket->fill($request->only([
            'passport_ref_no',
            'seat'
        ]))->save();

        return response()->json([
            'status' => 'success',
            'ticket' => $ticket
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Fetch ticket with associated flight
        $ticket = Ticket::with('flight')->findOrFail($id);

        // Make sure current user owns this flight
        if(!$ticket->flight->isOwnedByCurrentUser())
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to handle this resource'
            ], 401);

        $ticket->cancelled_at = Carbon::now()->toDateTimeString();
        $ticket->save();

        return response()->json([
            'status' => 'success',
            'ticket' => $ticket
        ], 201);
    }
}
