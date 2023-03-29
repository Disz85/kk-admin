<?php

namespace App\Console\Commands\Import;

use App\Helpers\Import\HtmlToEditorJsConverterIngredient;
use App\Models\Ingredient;
use App\Models\User;
use App\XMLReaders\IngredientXMLReader;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportIngredient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "import:ingredient
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Imports ingredients from XML file";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(IngredientXMLReader $ingredientXMLReader, HtmlToEditorJsConverterIngredient $converterIngredient): void
    {
        $skipped = 0;
        $path = $this->option("path");
        $deleteIfExist = $this->option("delete");

        $progress = $this->output->createProgressBar($ingredientXMLReader->count($path));
        $progress->start();

        $ingredientXMLReader->read($path, function (array $data) use ($deleteIfExist, &$skipped, $progress, $converterIngredient): void {
            $ingredient = Ingredient::where("legacy_id", "=", $data["id"])->first() ?? new Ingredient();

            if ($ingredient->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }

                $ingredient->delete();
                $ingredient = new Ingredient();
            }

            try {
                if (key_exists("description", $data)) {
                    $ingredient->description = $converterIngredient->convert($data["description"], "article") ?? null;
                }
                //"Noneú" --> ez így kell, így van az XML-ben
                if (key_exists("ewgdata", $data) && ($data["ewgdata"] === "Noneú" || $data["ewgdata"] === "n")) {
                    $data["ewgdata"] = "None";
                }

                $creator = User::query()
                    ->where('legacy_nickname', '=', $data['cretby'])
                    ->first()
                    ?->id;

                $ingredient->legacy_id = $data["id"];
                $ingredient->name = $data["title"] ?? null;
                $ingredient->slug = $data["slug"] ?? null;
                $ingredient->ewg_data = $data["ewgdata"] ?? null;
                $ingredient->ewg_score = $data["ewgscore"] ?? null;
                $ingredient->ewg_score_max = $data["ewgscoremax"] ?? null;
                $ingredient->created_at = $data["creton"] ?? null;
                $ingredient->updated_at = $data["modon"] ?? null;
                $ingredient->published_at = $data["isapproved"] ? Carbon::now() : null;
                $ingredient->comedogen_index = $data["comedogenicindex"] ?? null;
                $ingredient->is_top = $data["topingredient"] ?? false;
                $ingredient->created_by = $creator;

                $ingredient->save();
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
