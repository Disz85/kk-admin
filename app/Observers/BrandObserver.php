<?php

namespace App\Observers;

use App\Models\Brand;
use App\Models\BrandChangeRequest;

class BrandObserver
{
    /**
     * Handle the Brand "deleting" event.
     *
     * @param Brand $brand
     * @return void
     */
    public function deleting(Brand $brand)
    {
        $brand->image()->delete();
        $brand->tags()->detach();

        BrandChangeRequest::where('brand_id', '=', $brand->id)->delete();
    }
}
