<?php

namespace App\Http\Requests\Fr24;

use App\Models\Fr24\Flight;
use Illuminate\Foundation\Http\FormRequest;

class StoreFlightTicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(Flight $flight): array
    {
        return [
            'passport_ref_no' => 'required|min:8|max:60|alpha_num',
            'seat' => 'integer|min:1|max:' . $flight->available_seats
        ];
    }
}
