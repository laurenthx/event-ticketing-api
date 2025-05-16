<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'price',
        'seat_info',
        'booking_time', // Though often set by DB default, can be fillable
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'booking_time' => 'datetime',
    ];

    /**
     * Get the user who booked the ticket.
     * A Ticket belongs to one User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for which the ticket was booked.
     * A Ticket belongs to one Event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}