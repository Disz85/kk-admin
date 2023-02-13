<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductChangeRequest;

class ProductObserver
{
    /**
     * Handle the Product "deleting" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleting(Product $product)
    {
        $product->image()->delete();
        $product->ingredients()->detach();
        $product->tags()->detach();
        $product->categories()->detach();

        ProductChangeRequest::where('product_id', '=', $product->id)->delete();
    }
}
