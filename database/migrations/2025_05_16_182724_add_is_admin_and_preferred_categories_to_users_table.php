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
        Schema::table('users', function (Blueprint $table) {
            // Add 'is_admin' column after the 'email' column (or any other existing column)
            // It will be a boolean (true/false) and default to false
            $table->boolean('is_admin')->default(false)->after('email');

            // Add 'preferred_categories' column after the 'is_admin' column
            // It will store JSON data (an array of strings), can be null
            $table->json('preferred_categories')->nullable()->after('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     * (This is what happens if you run 'php artisan migrate:rollback')
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // If rolling back, remove these columns
            $table->dropColumn('is_admin');
            $table->dropColumn('preferred_categories');
        });
    }
};