<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;

class FlightsTest extends TestCase
{

    /**
     * Test that a user can create a flight.
     */
    public function test_a_user_can_create_a_flight(): void
    {

        Sanctum::actingAs(
            User::findOrFail(1)
        );

        $response = $this->postJson('/api/fr24/v1/flights', [
            'origin' => 'ARN',
            'destination' => 'NRT',
            'departure_time' => '2012-04-23 18:25:00',
            'arrival_time' => '2012-04-24 13:45:00',
            'available_seats' => 32
        ]);
        
        $response
        ->assertStatus(201)
        ->assertJson([
            'status' => 'success'
        ]);
    }

    /**
     * Test that a user can't create an erroneous flight.
     */
    public function test_a_user_cannot_create_an_erroneous_flight(): void
    {

        Sanctum::actingAs(
            User::findOrFail(1)
        );

        $response = $this->postJson('/api/fr24/v1/flights', [
            'origin' => 'ARn',
            'destination' => 'NRT',
            'departure_time' => '2012-04-23 18:25:00',
            'arrival_time' => '2012-04-24 13:45:00',
            'available_seats' => 35
        ]);
        
        $response
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'origin', 'available_seats'
        ]);
    }


    /**
     * Test that a user can view a flight.
     */
    public function test_a_user_can_view_a_flight(): void
    {

        Sanctum::actingAs(
            User::findOrFail(1)
        );

        $response = $this->get('/api/fr24/v1/flights/1');
        
        $response
        ->assertStatus(200)
        ->assertJson([
            'id' => 1,
            'origin' => 'ARN',
            'destination' => 'NRT',
            'departure_time' => '2012-04-23 18:25:00',
            'arrival_time' => '2012-04-24 13:45:00',
            'available_seats' => 32
        ]);
    }

    /**
     * Test that a user cannot view a flight he does not own.
     */
    public function test_a_user_cannot_view_a_flight_he_does_not_own(): void
    {

        Sanctum::actingAs(
            User::findOrFail(2)
        );

        $response = $this->get('/api/fr24/v1/flights/1');
        
        $response
        ->assertStatus(401)
        ->assertJson([
            'status' => 'error'
        ]);
    }

}
