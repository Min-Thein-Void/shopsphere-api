<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Validation\ValidationException;

class AdminProductService
{
    public function setDiscountLogic($data, $id)
    {
        $product = Product::findOrFail($id);

        if (! $data['discount_type']) {
            $product->update([
                'discount_type' => null,
                'discount_value' => null,
            ]);

            return $product->fresh();
        }

        if ($data['discount_type'] === 'percentage' && $data['discount_value'] > 100) {
            throw ValidationException::withMessages([
                'discount_value' => 'Percentage discount cannot exceed 100',
            ]);
        }

        $product->update($data);

        return $product->fresh();
    }
}
