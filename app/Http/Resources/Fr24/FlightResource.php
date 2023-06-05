<?php

namespace App\Http\Resources\Fr24;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'available_seats' => $this->available_seats,
        ];
    }
}
