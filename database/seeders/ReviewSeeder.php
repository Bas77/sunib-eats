<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get the restaurants and users we can assign reviews to.
        $restaurants = Restaurant::all();
        
        // Let's create two sample users if they don't exist
        $user1 = User::firstOrCreate(
            ['email' => 'jane.doe@example.com'],
            ['name' => 'Jane Doe', 'password' => bcrypt('password')]
        );
        
        $user2 = User::firstOrCreate(
            ['email' => 'john.smith@example.com'],
            ['name' => 'John Smith', 'password' => bcrypt('password')]
        );

        // 2. Define the review data.
        $reviews = [
            [
                'restaurant_name' => 'Geprek Bensu',
                'user_id' => $user1->id,
                'rating' => 5,
                'comment' => 'Amazing food! The best geprek in town. A must-try for students!',
                'price' => 25000,
                'image_url' => 'https://images.unsplash.com/photo-1626202243202-731362e26915?q=80&w=2070&auto=format&fit=crop',
                'upvotes' => 28,
                'downvotes' => 1,
            ],
            [
                'restaurant_name' => 'Geprek Bensu',
                'user_id' => null, // Anonymous review
                'rating' => 4,
                'comment' => 'The sambal is fantastic and the prices are unbeatable for a student budget.',
                'price' => 22000,
                'upvotes' => 15,
                'downvotes' => 0,
            ],
            [
                'restaurant_name' => 'Mama Djempol',
                'user_id' => $user2->id,
                'rating' => 4,
                'comment' => 'Good value for the price, but can be a bit crowded during lunch hour.',
                'price' => 28000,
                'upvotes' => 8,
                'downvotes' => 3,
            ],
            [
                'restaurant_name' => 'Pizza Hut',
                'user_id' => $user1->id,
                'rating' => 4,
                'comment' => 'Classic pizza, you know what you\'re getting. Good for groups.',
                'price' => 85000,
                'upvotes' => 11,
                'downvotes' => 2,
            ],
        ];

        // 3. Loop through the data and create the reviews.
        foreach ($reviews as $reviewData) {
            // Find the restaurant by name
            $restaurant = $restaurants->firstWhere('name', $reviewData['restaurant_name']);

            // Create the review if the restaurant exists
            if ($restaurant) {
                Review::create([
                    'restaurant_id' => $restaurant->id,
                    'user_id' => $reviewData['user_id'],
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'],
                    'price' => $reviewData['price'],
                    'image_url' => $reviewData['image_url'] ?? null,
                    'upvotes' => $reviewData['upvotes'],
                    'downvotes' => $reviewData['downvotes'],
                ]);
            }
        }
    }
}