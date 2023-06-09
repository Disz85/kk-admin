<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AuthorCollection;
use App\Http\Resources\Admin\BrandCollection;
use App\Http\Resources\Admin\CategoryCollection;
use App\Http\Resources\Admin\IngredientCollection;
use App\Http\Resources\Admin\TagCollection;
use App\Models\Author;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function authors(Request $request): AuthorCollection
    {
        return new AuthorCollection(
            Author::where('name', 'like', '%' . $request->get('name') . '%')
                ->when(
                    $request->has('withoutId'),
                    fn (Builder $query) => $query->where('id', '!=', $request->get('withoutId'))
                )
                ->orderBy('name')
                ->get()
        );
    }

    public function tags(Request $request): TagCollection
    {
        return new TagCollection(
            Tag::where('name', 'LIKE', '%' . $request->get('name') . '%')
                ->when(
                    $request->has('withoutId'),
                    fn (Builder $query) => $query->where('id', '!=', $request->get('withoutId'))
                )
                ->orderBy('name')
                ->get()
        );
    }

    public function categories(string $type, Request $request): CategoryCollection
    {
        return new CategoryCollection(
            Category::query()
                ->where('type', '=', $type)
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('name', 'like', '%' . $request->get('name') . '%')
                )
                ->when(
                    $request->has('withoutId'),
                    fn (Builder $query) => $query->where('id', '!=', $request->get('withoutId'))
                )
                ->orderBy('name')
                ->get()
        );
    }

    public function brands(Request $request): BrandCollection
    {
        return new BrandCollection(
            Brand::where('title', 'LIKE', '%' . $request->get('title') . '%')
                ->when(
                    $request->has('withoutId'),
                    fn (Builder $query) => $query->where('id', '!=', $request->get('withoutId'))
                )
                ->orderBy('title')
                ->get()
        );
    }

    public function ingredients(Request $request): IngredientCollection
    {
        return new IngredientCollection(
            Ingredient::where('name', 'LIKE', '%' . $request->get('name') . '%')
                ->when(
                    $request->has('withoutId'),
                    fn (Builder $query) => $query->where('id', '!=', $request->get('withoutId'))
                )
                ->orderBy('name')
                ->get()
        );
    }
}
