<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory; // Enables using factories for this model

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'location',
        'capacity',
    ];

    /**
     * Get the events for the venue.
     * A Venue can have many Events.
     */
    public function events()
    {
        // Assumes Event model will be in App\Models namespace
        return $this->hasMany(Event::class);
    }
}