<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AuthorCollection;
use App\Http\Resources\Admin\CategoryCollection;
use App\Http\Resources\Admin\TagCollection;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function authors(Request $request): AuthorCollection
    {
        return new AuthorCollection(
            Author::where('name', 'like', '%' . $request->get('name') . '%')
                ->orderBy('name')
                ->get()
        );
    }

    public function tags(Request $request): TagCollection
    {
        return new TagCollection(
            Tag::where('name', 'LIKE', '%' . $request->get('name') . '%')
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
                ->orderBy('name')
                ->get()
        );
    }
}
