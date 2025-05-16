<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'venue_id',
        'user_id', // Creator (admin)
        'category',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2', // Cast price to a decimal with 2 places
    ];

    /**
     * Get the venue where the event takes place.
     * An Event belongs to one Venue.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the user (admin) who created the event.
     * An Event belongs to one User (its creator).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id'); // Explicitly specify foreign key 'user_id'
    }

    /**
     * Get the tickets for the event.
     * An Event can have many Tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }
}