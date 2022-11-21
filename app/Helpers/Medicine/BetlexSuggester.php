<?php

namespace App\Helpers\Medicine;

use App\Models\PharmIndex\Product;
use App\Models\ProfessionalContent;
use App\Models\Tag;
use Illuminate\Support\Collection;

class BetlexSuggester
{
    /**
     * Finds betlex entries based on the tags of the medicine product.
     * @param Product $product
     * @return Collection
     */
    public function suggest(Product $product): Collection
    {
        $suggestion_count = config('medicine.suggester.betlex.suggestion_count');
        $tags = $product->getTags();
        $betlexes = collect();

        foreach ($tags as $tag) {
            /** @var Tag $tag */
            $betlexes = $betlexes->merge(
                $tag->professional_contents()->ofType(ProfessionalContent::TYPE_BETLEX)->get()
            );
        }

        return $betlexes
            ->unique('id')
            ->shuffle()
            ->take($suggestion_count);
    }
}
