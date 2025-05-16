<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Optional, keep commented if not using email verification
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // <-- CORRECTED: Use Passport's HasApiTokens

class User extends Authenticatable // Potentially 'implements MustVerifyEmail'
{
    // CORRECTED: Use Passport's HasApiTokens for API authentication
    // HasFactory and Notifiable are standard and good to keep.
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',             // <-- ADDED: For admin role management
        'preferred_categories', // <-- ADDED: For bonus feature (user preferences)
    ];

    /**
     * The attributes that should be hidden for serialization.
     * (When the model is converted to an array or JSON)
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',            // <-- ADDED: Cast is_admin to boolean
        'preferred_categories' => 'array',  // <-- ADDED: Cast preferred_categories to array (for JSON column)
    ];

    /**
     * Helper method to easily check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin; // Access the 'is_admin' attribute
    }

    /**
     * Define the relationship: A User can have many Tickets.
     * (We will create the Ticket model later)
     */
    public function tickets()
    {
        // Forward reference to Ticket class, assumes Ticket model will be in App\Models namespace
        return $this->hasMany(\App\Models\Ticket::class);
    }
}