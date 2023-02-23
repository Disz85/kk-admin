<?php

namespace App\Resources\Elastic;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * @param $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'canonical_name' => $this->canonical_name,
            'published_at' => new Carbon($this->published_at),
            'category_hierarchy' => $this->whenLoaded(
                'categories',
                CategoryResource::collection(
                    Category::ancestorsAndSelf($this->productCategory)->toFlatTree()
                )
            ),
            'brand' => $this->whenLoaded('brand', new ProductBrandResource($this->brand)),
            'ingredients' => $this->whenLoaded(
                'ingredients',
                ProductIngredientResource::collection($this->ingredients)
            ),
            'skin_types' => $this->whenLoaded(
                'categories',
                CategoryResource::collection($this->skinTypeCategories)
            ),
            'skin_concerns' => $this->whenLoaded(
                'categories',
                CategoryResource::collection($this->skinConcernCategories)
            ),
            'hair_problems' => $this->whenLoaded(
                'categories',
                CategoryResource::collection($this->hairProblemCategories)
            ),
        ];
    }
}
