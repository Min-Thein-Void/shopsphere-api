<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function findWithLock($id)
    {
        return Product::lockForUpdate()->find($id);
    }

    // public function reduceStock($product, $quantity)
    // {
    //     $product->stock -= $quantity;
    //     $product->save();
    // }
}
