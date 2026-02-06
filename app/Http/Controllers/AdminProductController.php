<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function StoreProduct(Request $request)
    {
        $formData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'image|mimes:jpg,png,jpeg,webp|max:2048',
            ]
        );

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $formData['image'] = $path;
        }

        $product = Product::create($formData);

        return response()->json(
            ['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }

    public function update(Request $request, Product $product)
    {

        $formDatas = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product->update($formDatas);

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product,
        ]);
    }
}
