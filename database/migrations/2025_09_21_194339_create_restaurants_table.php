<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            // Core Details
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('google_place_id')->nullable()->unique();
            $table->text('description')->nullable();

            // Location Details
            $table->string('cuisine')->index();
            $table->string('area')->index();
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // App-Specific Data
            $table->decimal('rating', 2, 1)->nullable()->index();
            $table->string('price_range');
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            
            // Timestamps
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};