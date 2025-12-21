<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function toggle(Request $request, Review $review)
    {
        $request->validate(['value' => 'required|in:1,-1']);
        // Validate input biar cuma bisa 1 atau -1
        $user = Auth::user();

        if ($review->user_id === $user->id) {
            return response()->json(['message' => 'Anda tidak bisa vote komentar sendiri.'], 403);
        }
        // Biar ga bisa vote komentar sendiri

        $voteValue = (int) $request->value;
        $existingVote = Vote::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->first();
        // BUat ngecek udah pernah vote atau belum

        if ($existingVote) {
            if ($existingVote->vote_value === $voteValue) {
                $existingVote->delete(); 
            } else {
                $existingVote->update(['vote_value' => $voteValue]);
            } // Logic untuk ngatur vote (ubah atau hapus)
        } else {
            Vote::create([
                'user_id' => $user->id,
                'review_id' => $review->id,
                'vote_value' => $voteValue
            ]);
        } // Bikkin vote baru

        return response()->json([
            'score' => $review->refresh()->score,
            'current_user_vote' => $review->current_user_vote
        ]);
    }
}
