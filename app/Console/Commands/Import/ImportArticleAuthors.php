<?php

namespace App\Console\Commands\Import;

use App\Models\Author;
use App\XMLReaders\ArticleAuthorXMLReader;
use Illuminate\Console\Command;

class ImportArticleAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:article-authors
                            {--path= : The path of the XML file}
                            {--delete : Deletes the existing records before saving}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports article authors from XML file';

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
    public function handle(ArticleAuthorXMLReader $articleAuthorXMLReader): void
    {
        $skipped = 0;
        $path = $this->option('path');
        $deleteIfExist = $this->option('delete');

        $progress = $this->output->createProgressBar($articleAuthorXMLReader->count($path));
        $progress->start();

        $articleAuthorXMLReader->read($path, function (array $data) use ($deleteIfExist, &$skipped, $progress) {
            $author = Author::query()
                ->where('legacy_id', '=', $data['id'])
                ->first()
                ?? new Author();

            if ($author->exists) {
                if (! $deleteIfExist) {
                    $skipped++;
                    $progress->advance();

                    return;
                }

                $author->delete();
                $author = new Author();
            }

            $author->legacy_id = $data['id'];
            $author->lastname = $data['lastname'] ?? null;
            $author->firstname = $data['firstname'] ?? null;
            $author->email = $data['email'] ?? null;
            $author->username = $data['username'] ?? null;

            $author->save();

            $progress->advance();
        });

        $progress->finish();

        $this->info("\nImporting is finished. Number of skipped records: " . $skipped);
    }
}
