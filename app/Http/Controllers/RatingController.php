<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rate(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        // Fetch updated product with avg rating
        $product = Product::withAvg('ratings', 'rating')->find($request->product_id);

        return response()->json([
            'message' => 'Rating saved',
            'rating' => $rating,
            'avgRating' => $product->ratings_avg_rating,
        ]);
    }

    public function average($productId)
    {
        $product = Product::withAvg('ratings', 'rating')->findOrFail($productId);

        return response()->json([
            'average' => round($product->ratings_avg_rating, 1),
        ]);
    }
}
