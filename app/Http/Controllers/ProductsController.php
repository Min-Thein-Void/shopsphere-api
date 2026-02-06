<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function AllProducts()
    {
        $products = Product::with('category')->get();

        return response()->json($products);
    }

    public function SingleProduct(Product $product)
    {
        return response()->json($product);
    }

    public function GetCategories()
    {
        $categories = Category::paginate(6);

        return response()->json($categories,200);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        return response()->json($products);
    }

    public function searchByCategory(Request $request)
    {
        $query = $request->input('c');

        $products = Product::whereHas('Category', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%");
        })->get();

        return response()->json($products);
    }

    public function photoUpload(Request $request, $id)
    {
        $request->validate(['avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp']);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user = User::findOrFail($id);
            $user->avatar = $path;
            $user->save();
        }

        return response()->json([
            'message' => 'Avatar uploaded successfully',
            'avatar' => asset('storage/'.$user->avatar),
        ]);
    }

    public function setDiscount(Request $request, Product $product)
    {
        $request->validate([
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        if (! $request->discount_type) {
            $product->update([
                'discount_type' => null,
                'discount_value' => null,
            ]);

            return response()->json([
                'message' => 'discount_remove',
                'product' => $product->fresh(),
            ]);
        }

        if ($request->discount_type === 'percentage' &&
    $request->discount_value > 100) {
            return response()->json([
                'message' => 'percentage discount cannot exceed 100',
            ], 422);
        }

        $product->update([
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
        ]);

        return response()->json([
            'message' => 'discount_updated',
            'product' => $product->fresh(),
        ]);
    }
}
