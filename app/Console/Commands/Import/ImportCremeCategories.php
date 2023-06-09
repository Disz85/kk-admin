<?php

namespace App\Console\Commands\Import;

use App\Enum\CategoryTypeEnum;
use App\Models\Category;
use App\XMLReaders\CremeCategoryXMLReader;
use Illuminate\Console\Command;

class ImportCremeCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:creme-categories
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports creme categories from XML file';

    public function handle(CremeCategoryXMLReader $categoryXMLReader): void
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($categoryXMLReader->count($path));
        $progress->start();

        $categoryXMLReader->read($path, function (array $data) use ($deleteIfExist, &$skipped, $progress): void {
            $parentLegacyId = null;
            $baseCategoryId = substr($data['id'], 0, 1);
            if ($baseCategoryId == 4 || $data['id'] == 1) {
                return;
            }
            if (str_contains($data['id'], '-')) {
                $parentLegacyId = substr($data['id'], 0, strrpos($data['id'], '-'));
            }
            if ($parentLegacyId == '1') {
                $parentLegacyId = null;
            }
            if (! is_null($parentLegacyId)) {
                $parent = Category::where('legacy_id', '=', $parentLegacyId)->first();
                $category = Category::where('slug', '=', $data['slug'])->where('parent_id', '=', $parent->id)->first() ?? new Category();
            } else {
                $category = Category::where('slug', '=', $data['slug'])->whereNull('parent_id')->first() ?? new Category();
            }

            if ($category->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }

                $category->delete();
                $category = new Category();
            }

            try {
                $category->name = $data['title'];
                $category->slug = $data['slug'];
                $category->legacy_id = $data['id'];
                switch ($baseCategoryId) {
                    case '2':
                        $category->type = CategoryTypeEnum::SkinType;

                        break;
                    case '3':
                        $category->type = CategoryTypeEnum::SkinConcern;

                        break;
                    default:
                        $category->type = CategoryTypeEnum::Product;
                }
                if (! is_null($parentLegacyId)) {
                    $category->parent_id = $parent->id;
                }
                $category->save();
            } catch (\Throwable $e) {
                $this->info("\nException: " . $e->getMessage());
            } finally {
                $progress->advance();
            }
        });

        $progress->finish();
        $this->info("\nImporting is finished. Number of skipped records: " . $skipped);
    }
}
