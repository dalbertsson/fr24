<?php

namespace App\Models\Fr24;

use App\Models\Fr24\Flight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    public $fillable = [
        'flight_id',
        'passport_ref_no',
        'seat'
    ];

    public function scopeBooked(Builder $query)
    {
        $query->where('cancelled_at', NULL);
    }

    public function flight() {
        return $this->belongsTo(Flight::class);
    }

}
