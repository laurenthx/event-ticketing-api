<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // Foreign key for the user who booked the ticket
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // If user is deleted, their tickets are also deleted

            // Foreign key for the event the ticket is for
            $table->foreignId('event_id')
                  ->constrained('events')
                  ->onDelete('cascade'); // If event is deleted, its tickets are also deleted

            $table->decimal('price', 8, 2); // Price paid for this specific ticket
            $table->string('seat_info')->nullable(); // e.g., "Section A, Row 5, Seat 12" or "General Admission"
            $table->timestamp('booking_time')->useCurrent(); // Defaults to the current timestamp when created

            $table->timestamps(); // created_at and updated_at

            // Optional: Indexes
            $table->index(['user_id', 'event_id']); // Composite index can be useful
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};