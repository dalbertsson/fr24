<?php

namespace App\Http\Controllers\Fr24;

use App\Models\Fr24\Flight;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Fr24\FlightResource;

class FlightsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FlightResource::collection(auth()->user()->flights);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required|uppercase|min:3|max:3',
            'destination' => 'required|uppercase|min:3|max:3',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'available_seats' => 'required|integer|min:1|max:32'
        ]);

        try {
            $flight = Flight::create(
                array_merge($request->all(), [
                    'user_id' => auth()->user()->id
                ])
            );
        } catch(\Exception $e) {

            // Note. In a "real" production app this would most 
            // likely be handled globally instead, along with some 
            // sort of notification to sysadmins.

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'flight' => new FlightResource($flight)
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        $flight = Flight::findOrFail($id);
        
        if(!$flight->isOwnedByCurrentUser())
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to view this resource'
            ], 401);

        return new FlightResource($flight);
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
