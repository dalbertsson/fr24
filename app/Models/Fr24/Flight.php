<?php

namespace App\Models\Fr24;

use App\Models\Fr24\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flight extends Model
{
    use HasFactory;

    public $hidden = [
        'user_id'
    ];

    public $fillable = [
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'available_seats',
        'user_id'
    ];

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function isOwnedByCurrentUser() {
        return $this->user_id == auth()->user()->id;
    }

}
