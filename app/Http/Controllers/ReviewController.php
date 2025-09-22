<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function vote(Request $request, Review $review)
    {
        try {
            $validated = $request->validate([
                'vote_type' => 'required|in:up,down'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Find if the user has already voted on this review
            $existingVote = ReviewVote::where('user_id', $user->id)
                ->where('review_id', $review->id)
                ->first();

            // Use a database transaction to ensure data integrity
            DB::transaction(function () use ($review, $user, $validated, $existingVote) {
                // Case 1: Retracting a vote (clicking the same button again)
                if ($existingVote && $existingVote->vote_type === $validated['vote_type']) {
                    $review->decrement($validated['vote_type'] === 'up' ? 'upvotes' : 'downvotes');
                    $existingVote->delete();
                }
                // Case 2: Changing a vote (e.g., from up to down)
                else if ($existingVote) {
                    // Decrement the old vote counter
                    $review->decrement($existingVote->vote_type === 'up' ? 'upvotes' : 'downvotes');
                    // Increment the new vote counter
                    $review->increment($validated['vote_type'] === 'up' ? 'upvotes' : 'downvotes');
                    // Update the vote type in the database
                    $existingVote->update(['vote_type' => $validated['vote_type']]);
                }
                // Case 3: Casting a completely new vote
                else {
                    ReviewVote::create([
                        'user_id' => $user->id,
                        'review_id' => $review->id,
                        'vote_type' => $validated['vote_type']
                    ]);
                    $review->increment($validated['vote_type'] === 'up' ? 'upvotes' : 'downvotes');
                }
            });

            // Return the fresh, final counts from the database
            return response()->json([
                'upvotes' => $review->fresh()->upvotes,
                'downvotes' => $review->fresh()->downvotes,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Vote error: ' . $e->getMessage(), [
                'review_id' => $review->id,
                'vote_type' => $request->input('vote_type'),
                'user_id' => auth()->id()
            ]);

            return response()->json(['error' => 'An error occurred while processing your vote'], 500);
        }
    }
}