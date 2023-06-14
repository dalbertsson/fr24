<?php

namespace App\Http\Requests\Fr24;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->ticket->flight->isOwnedByCurrentUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'passport_ref_no' => 'min:8|max:60|alpha_num',
            'seat' => 'integer|min:1|max:' . $this->ticket->flight->available_seats
        ];
    }
}
