<?php

namespace App\Console\Commands\Import;

use App\Enum\TagTypeEnum;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AddTypeToBrandLabels extends Command
{
    protected $signature = 'import:add-type-to-brand-tags';

    protected $description = 'Adds type to brand tags, and removes the 4 basic type tags';

    public function handle(): void
    {
        $tags = Tag::query()->whereNotNull('legacy_id')->get();

        $this->addTypeToTagsLike($tags, '/1-./', TagTypeEnum::BRAND_CATEGORY);
        $this->addTypeToTagsLike($tags, '/2-./', TagTypeEnum::SALES_OUTLET);
        $this->addTypeToTagsLike($tags, '/3-./', TagTypeEnum::NATIONALITY);
        $this->addTypeToTagsLike($tags, '/4-./', TagTypeEnum::COMPANY);

        $tags->whereIn('legacy_id', [1, 2, 3, 4])->each(fn (Tag $tag) => $tag->delete());
    }

    private function addTypeToTagsLike(Collection $tags, string $match, TagTypeEnum $type): void
    {
        $filteredTags = $tags->filter(fn (Tag $tag) => preg_match($match, $tag->legacy_id));

        foreach ($filteredTags as $tag) {
            $tag->type = $type->value;
            $tag->save();
        }
    }
}
