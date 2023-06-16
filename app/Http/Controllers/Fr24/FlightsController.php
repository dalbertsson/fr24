<?php

namespace App\Http\Controllers\Fr24;

use App\Models\Fr24\Flight;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Fr24\FlightResource;
use App\Http\Requests\Fr24\StoreFlightRequest;
use App\Http\Middleware\Fr24\EnsureUserOwnsFlight;

class FlightsController extends Controller
{

    public function __construct() {
        $this->middleware(EnsureUserOwnsFlight::class, ['only' => ['show']]);
    }

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
    public function store(StoreFlightRequest $request)
    {

        // Store flight
        try {
            $flight = Flight::create(
                array_merge($request->validated(), [
                    'user_id' => auth()->user()->id
                ])
            );
        } catch(\Exception $e) {

            // Note. In a "real" production app this should most 
            // likely be handled globally instead, along with some 
            // sort of notification to sysadmins.

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }

        // Return response
        return response()->json([
            'status' => 'success',
            'flight' => new FlightResource($flight)
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Flight $flight)
    {
        return new FlightResource($flight);
    }
}
