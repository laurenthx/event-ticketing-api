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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');

            // Foreign key for the venue
            $table->foreignId('venue_id')
                  ->constrained('venues') // Assumes 'venues' table and 'id' column on it
                  ->onDelete('cascade'); // If a venue is deleted, delete its events

            // Foreign key for the admin user who created the event
            $table->foreignId('user_id') // Refers to the 'id' on the 'users' table
                  ->constrained('users')
                  ->onDelete('cascade'); // If the creator user is deleted, delete their events

            $table->string('category')->nullable(); // For filtering (e.g., Music, Sports, Conference)
            $table->decimal('price', 8, 2)->default(0.00); // Base price for the event

            $table->timestamps(); // created_at and updated_at

            // Optional: Add indexes for columns frequently used in queries
            $table->index('start_time');
            $table->index('category');
            $table->index('venue_id'); // Already indexed by foreign key constraint, but explicit can be good.
            $table->index('user_id');  // Already indexed by foreign key constraint.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};