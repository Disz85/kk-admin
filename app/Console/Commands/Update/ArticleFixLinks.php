<?php

namespace App\Console\Commands\Update;

use App\Helpers\Import\HtmlToEditorJsConverterBlogger;
use App\Helpers\Import\HtmlToEditorJsConverterMagazine;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ArticleFixLinks extends Command
{
    private const KREMMANIA_URL = "https://kremmania.hu/";
    private const KERESES_HIREK_URL = "kereses/hirek/";

    private string $wrongArticleLinks = "";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:fix-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Replace article's body URLS";

    /**
     * @param HtmlToEditorJsConverterMagazine $magazineConverter
     * @param HtmlToEditorJsConverterBlogger $blogConverter
     * @return void
     */
    public function handle(HtmlToEditorJsConverterMagazine $magazineConverter, HtmlToEditorJsConverterBlogger $blogConverter): void
    {
        $articles = Article::all();
        $category = Category::where('slug', 'LIKE', 'hirek')->first();
        $notFoundArticle = Article::where('slug', 'LIKE', 'not-found-article-links')->first();

        if ($category === null) {
            $category = new Category();
            $category->name = 'Hírek';
            $category->type = 'article';

            $category->save();
        }
        if ($notFoundArticle === null) {
            $notFoundArticle = new Article();
            $notFoundArticle->title = 'Rossz linkek cseréje';
            $notFoundArticle->slug = 'not-found-article-links';

            $notFoundArticle->save();
        }

        $progress = $this->output->createProgressBar($articles->count());
        $progress->start();

        foreach ($articles as $article) {
            if (! empty($article->body) && ! Arr::accessible($article->body)) {
                preg_match_all('/\b(?:(?:http?|https):\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $article->body, $matches);

                if (! empty($matches[0])) {
                    $matches = array_values(array_unique($matches[0]));
                    foreach ($matches as $url) {
                        $parseUrl = parse_url($url);
                        if ($parseUrl['host'] !== "kremmania.hu" && (Str::contains($parseUrl['host'], 'magazin.kremmania') || Str::contains($parseUrl['host'], 'blog.kremmania')) && ! Str::contains($parseUrl['path'], 'upload')) {
                            $parseUrl = $this->trimUrl($article, $category, $parseUrl, $url);
                            if (Arr::exists($parseUrl, 'newUrl')) {
                                $article->body = Str::replace($url, self::KREMMANIA_URL.$parseUrl['newUrl'], $article->body);
                                $article->save();
                            }
                        }
                    }
                }

                try {
                    switch (true) {
                        case $article->legacy_id > 1000000:
                            $article->body = $article->body ? $blogConverter->convert($article->body, 'article') : null;

                            break;
                        case $article->legacy_id < 1000000:
                            $article->body = $article->body ? $magazineConverter->convert($article->body, 'article') : null;

                            break;
                    }
                } catch (\Throwable $e) {
                    $this->info("\nException: " . $e->getMessage(). ' ArticleId: '.$article->id);
                } finally {
                    $article->save();
                }
            }
            $progress->advance();
        }

        $notFoundArticle->body = $magazineConverter->convert($this->wrongArticleLinks, 'article');
        $notFoundArticle->save();

        $progress->finish();

        $this->info("\nNot found article links: ".self::KREMMANIA_URL."admin/articles/".$notFoundArticle->id);
    }

    /**
     * @param Article $article
     * @param Category $category
     * @param array $parseUrl
     * @param string $url
     * @return array
     */
    private function trimUrl(Article $article, Category $category, array $parseUrl = [], string $url = ""): array
    {
        if (empty($parseUrl) && empty($url)) {
            return $parseUrl;
        }

        switch($parseUrl['host']) {
            case 'magazin.kremmania.hu':
                $parseUrl['newUrl'] = Str::substr($parseUrl['path'], 0, -1);

                break;
            case 'blog.kremmania.hu':
                if (Str::contains($parseUrl['path'], 'search')) {
                    $queryPart = last(explode('/', $parseUrl['path']));


                    if (Arr::exists($parseUrl, 'query') && Str::contains($parseUrl['query'], 'q=')) {
                        $queryPart = Str::substr($parseUrl['query'], 2);
                    }
                    $parseUrl['queryUrl'] = self::KERESES_HIREK_URL.$queryPart;
                }
                if (! Arr::exists($parseUrl, 'newUrl')) {
                    $parseUrl['newUrl'] = Str::substr($parseUrl['path'], 0, -5);
                }

                break;
        }

        if (Arr::exists($parseUrl, 'newUrl')) {
            $parseUrl['newUrl'] = Str::substr($parseUrl['newUrl'], 1);
            $explodedUrl = explode('/', $parseUrl['newUrl']);
            $legacyArticle = Article::where('legacy_slug', 'LIKE', '%'.last($explodedUrl).'%')->first();
            if ($legacyArticle) {
                $parseUrl['newUrl'] = $category->slug.'/'.$legacyArticle->slug;
            } else {
                $this->wrongArticleLinks .= "Article Id: ".$article->id.", Wrong link: ".$url.".<br/>";
            }
        }

        return $parseUrl;
    }
}
