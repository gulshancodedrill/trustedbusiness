<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewVoteController extends Controller
{
    public function vote(Request $request, Review $review)
    {
        $validated = $request->validate([
            'vote' => ['required', 'in:like,dislike'],
        ]);

        $voteValue = $validated['vote'] === 'like' ? 1 : -1;

        $existing = $review->votes()
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            // Toggle the vote off if the same vote is clicked again.
            if ((int) $existing->vote === $voteValue) {
                $existing->delete();
            } else {
                $existing->update(['vote' => $voteValue]);
            }
        } else {
            $review->votes()->create([
                'user_id' => Auth::id(),
                'vote' => $voteValue,
            ]);
        }

        return redirect()
            ->route('business.detail', $review->business)
            ->with('status', 'Vote saved.');
    }
}

