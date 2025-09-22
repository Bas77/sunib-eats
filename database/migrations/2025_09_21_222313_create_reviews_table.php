<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // The link to the restaurant and user
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            
            // The review content
            $table->unsignedTinyInteger('rating'); // Stores a number from 0-255, perfect for 1-5 stars
            $table->text('comment');
            $table->unsignedInteger('price')->nullable(); // e.g., 25000 for Rp 25.000
            $table->string('image_url')->nullable();
            
            // Voting
            $table->unsignedInteger('upvotes')->default(0);
            $table->unsignedInteger('downvotes')->default(0);
            
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};