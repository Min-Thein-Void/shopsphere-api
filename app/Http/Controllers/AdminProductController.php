<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\AdminProductService;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected $adminProductService;

    public function __construct(AdminProductService $adminProductService)
    {
        $this->adminProductService = $adminProductService;
    }

    public function StoreProduct(StoreProductRequest $request)
    {
        $formData = $request->validated();

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

    public function update(UpdateProductRequest $request, Product $product)
    {

        $formDatas = $request->validated();

        $product->update($formDatas);

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product,
        ]);
    }

    public function setDiscount(Request $request, $id)
    {
        $data = $request->validate([
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        $product = $this->adminProductService->setDiscountLogic($data, $id);

        return response()->json([
            'message' => 'discount_updated',
            'product' => $product->fresh(),
        ]);
    }
}
