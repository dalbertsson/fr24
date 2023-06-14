<?php

namespace App\Http\Requests\Fr24;

use Illuminate\Foundation\Http\FormRequest;

class StoreFlightRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'origin' => 'required|uppercase|min:3|max:3',
            'destination' => 'required|uppercase|min:3|max:3',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'available_seats' => 'required|integer|min:1|max:32'
        ];
    }
}
