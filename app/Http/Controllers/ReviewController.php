<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        $review = $business->reviews()->updateOrCreate(
            [
                'user_id' => Auth::id(),
                'business_id' => $business->id,
            ],
            [
                'rating' => (int) $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return redirect()
            ->route('business.detail', $business)
            ->with('status', 'Review submitted successfully.');
    }
}

