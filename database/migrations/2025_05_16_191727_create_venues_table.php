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
        Schema::create('venues', function (Blueprint $table) {
            $table->id(); // Auto-incrementing BigInt primary key
            $table->string('name'); // Venue name, e.g., "Grand Concert Hall"
            $table->string('location'); // Venue location, e.g., "123 Main St, Anytown, USA"
            $table->integer('capacity'); // Maximum number of attendees
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venues');
    }
};