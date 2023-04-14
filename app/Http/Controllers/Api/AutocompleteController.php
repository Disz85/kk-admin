<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Autocomplete\BrandCollection;
use App\Models\Brand;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function brands(Request $request): BrandCollection
    {
        return new BrandCollection(
            Brand::query()
                ->where('title', 'LIKE', $request->input('name') . '%')
                ->orderBy('title')
                ->get()
        );
    }
}
