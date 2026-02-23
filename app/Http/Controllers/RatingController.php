<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function rate(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = Rating::updateOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ], [
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => 'Rating saved',
            'rating' => $rating,
        ]);
    }

    public function average($productId)
    {
        $avg = Rating::where('product_id', $productId)->avg('rating');

        return response()->json([
            'average' => round($avg, 1),
        ]);
    }
}
