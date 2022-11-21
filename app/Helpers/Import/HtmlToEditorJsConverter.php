<?php

namespace App\Helpers\Import;

use App\Helpers\ImportImage;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\AbstractNode;
use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Dom\Node\TextNode;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use PHPHtmlParser\Exceptions\Tag\AttributeNotFoundException;
use PHPHtmlParser\Exceptions\UnknownChildTypeException;
use Illuminate\Support\Str;
use stringEncode\Exception;

class HtmlToEditorJsConverter
{
    private ImportImage $save_image;

    public function __construct(ImportImage $save_image)
    {
        $this->save_image = $save_image;
    }

    /**
     * Returns an EditorJS compatible array data from HTML content
     * @param string $html
     * @return array
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     * @throws UnknownChildTypeException
     */
    public function convert(string $html, string|null $type = null): array
    {
        if ($type == 'article') {

            $translate_article = [
                '<div class="box-top-cikkek-ajanlo">[TOP-CIKKEK-AJANLO]</div>' => '',
                '<a href="http:' => '<a href="https:',
                '<a href="//' => '<a href="https://',
                '<a href="www.hazipatika.com' => '<a href="https://www.hazipatika.com',
                '<a href="hazipatika.com' => '<a href="https://www.hazipatika.com',
                '<h2 class="bekezdes">' => '<h2>',
                '<blockquote>' => '',
                '</blockquote>' => '',
                '<p class="header">' => '<p>',
                '<p class="nomargin">' => '<p>',
                '<em>' => '',
                '</em>' => ''
            ];
            $html = strtr($html, $translate_article);
        }

        $translate = [
            '&amp;' => '&',
            '<link' => '<a',
            '</link>' => '</a>',
        ];
        $html = strtr($html, $translate);

        $dom = new Dom();
        $dom->loadStr($html);

        $result = [];
        $result['blocks'] = $this->iterateNodes($dom, $type);

        return $result;
    }

    /**
     * Returns an EditorJS compatible array data from HTML content
     * @param string $text
     * @return array
     */
    public function convertArticleDescription(string $text): array
    {
        $result['blocks'][] = $this->makeBlock($text, 'paragraph', 'paragraph');
        return $result;
    }

    /**
     * Iterates over the top level nodes of the given HTML content.
     * Returns the generated EditorJS blocks.
     * @param Dom $dom
     * @return array
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     * @throws UnknownChildTypeException
     */
    private function iterateNodes(Dom $dom, string|null $type = null, int $recursionDepth = 1): array
    {
        if ($recursionDepth > 10) {
            throw new \Exception('Maximum recursion level exceeded while processing the HTML content.');
        }

        $blocks = [];

        /** @var AbstractNode $node */
        foreach ($dom->getChildren() as $node) {
            if ($node instanceof HtmlNode) {
                $text = $node->innerHtml();
                $tag = $node->tag->name();

                if ((in_array($type, ['article', 'betlex', 'examination', 'labresult', 'herbal'])) && $tag == 'p') {
                    $this->processSubContent($node, $blocks, $type, $recursionDepth);
                } else if ($type == 'vitamin' && $tag == 'table') {
                    $text = str_replace('&amp;', '&', $text);
                    $blocks[] = $this->makeBlock($text, 'table');
                } else {
                    switch ($tag) {
                        // article figure
                        case 'figure':
                            try {
                                $blocks[] = $this->makeBlock($text, $tag);
                            } catch (\Exception $e) {
                                var_dump($e->getMessage());
                            }
                            break;
                        // article img
                        case 'img':
                        // Betlex special image
                        case 'kep':
                            try {
                                // In case of img tag, the outer html is needed to process
                                $blocks[] = $this->makeBlock($node->outerHtml(), $tag, $type);
                            } catch (\Exception $e) {
                                var_dump($e->getMessage());
                            }
                            break;
                        case 'div':
                            $tag_classes = '';
                            if ($node->tag->hasAttribute('class')) {
                                $tag_classes = $node->tag->getAttribute('class')->getValue();
                            }

                            // article gallery
                            if (Str::contains($tag_classes, 'imagesSlideshowHp')) {
                                $li_items = $node->find('li');
                                $gallery_items = [];

                                foreach ($li_items as $li_item) {
                                    if ($li_item instanceof HtmlNode) {
                                        $image_source = null;
                                        $description = '';

                                        foreach ($li_item->getChildren() as $child) {
                                            if ($child instanceof HtmlNode && $child->tag->name() === 'img') {
                                                $image_source = $child->getAttribute('src');
                                            }

                                            if ($child instanceof HtmlNode && $child->tag->name() === 'h2') {
                                                $description .= $child->innerText();
                                            }

                                            if ($child instanceof TextNode) {
                                                if (!empty($description)) {
                                                    $description .= '. ';
                                                }

                                                $description .= $child->innerHtml(); // Has no innerText method
                                            }
                                        }

                                        if ($image_source !== null) {
                                            $gallery_items[] = [
                                                'image' => $image_source,
                                                'description' => $description,
                                            ];
                                        }
                                    }
                                }

                                if (count($gallery_items) > 0) {
                                    $gallery = [];
                                    $gallery['type'] = 'gallery';
                                    $gallery['data'] = ['title' => ''];

                                    foreach ($gallery_items as $gallery_item) {
                                        $image_data = $this->save_image->saveImage2(trim($gallery_item['image']), 'import');

                                        $gallery['data']['gallery_items'][] = [
                                            'id' => $image_data->id,
                                            'path' => $image_data->path,
                                            'description' => $gallery_item['description'],
                                        ];
                                    }

                                    $blocks[] = $gallery;
                                }
                            }

                            // article image slider
                            if (Str::contains($tag_classes, 'imagesSliderHp')) {
                                preg_match_all('/src=\"(.*?)\"/', $text, $slides2);

                                if ($slides2) {
                                    $gallery = [];
                                    $gallery['title'] = '';
                                    $gallery['type'] = 'gallery';
                                    $gallery['data'] = ['title' => ''];

                                    for ($i = 0; $i < count($slides2[1]); $i++) {
                                        $image_data = $this->save_image->saveImage2(trim($slides2[1][$i]), 'import');

                                        $gallery['data']['gallery_items'][] = [
                                            'id' => $image_data->id,
                                            'path' => $image_data->path,
                                            'description' => '',
                                        ];
                                    }

                                    $blocks[] = $gallery;
                                }
                            }

                            if (
                                // Examples:
                                // <div class="col-uxs-12 col-xs-12 col-sm-12 marginbottom-20 cikkdoboz box-video">
                                // <div class="embed-responsive embed-responsive-16by9 embed-responsive-item b-lazy newytembed">
                                // <div itemscope="" itemtype="https://schema.org/VideoObject">
                                // <div style="position:relative; padding-bottom:56.25%; overflow:hidden;">
                                Str::contains($tag_classes, 'box-video') ||
                                Str::contains($tag_classes, 'embed-responsive') ||
                                $node->getAttribute('itemtype') === 'https://schema.org/VideoObject' ||
                                // Special embed case in betlexes
                                ($type === 'betlex' && $node->getAttribute('style') === 'position:relative; padding-bottom:56.25%; overflow:hidden;')
                            ) {
                                $iframes = $node->find('iframe');

                                if ($iframes && count($iframes) > 0) {
                                    /** @var HtmlNode $iframe */
                                    foreach ($iframes as $iframe) {
                                        $blocks[] = $this->makeBlock($iframe->outerHtml(), 'iframe');
                                    }
                                } else {
                                    $metas = $node->find('meta[itemprop="contentUrl"]');

                                    if ($metas && count($metas) > 0) {
                                        foreach ($metas as $meta) {
                                            /** @var HtmlNode $meta */
                                            $content = $meta->getAttribute('content');

                                            if ($content) {
                                                $parsed_content = parse_url($content);

                                                if (Str::contains($parsed_content['host'], ['content.jwplatform.com', 'cdn.jwplayer.com'])) {
                                                    $video_id = pathinfo(basename($content), PATHINFO_FILENAME);

                                                    if ($video_id) {
                                                        //The Embed plugin works with iframes, pass an iframe alternative for processing
                                                        $blocks[] = $this->makeBlock('<iframe src="' . $parsed_content['scheme'] . '://' . $parsed_content['host'] . '/players/' . $video_id . '.html' . '"></iframe>', 'iframe');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (Str::contains($tag_classes, 'cikkdoboz') && !Str::contains($tag_classes, 'box-video')) {
                                $result = $this->tryMakeSpecialBlock($text, 'highlighted');

                                if ($result) {
                                    $blocks[] = $result;
                                }
                            }

                            if (Str::contains($tag_classes, 'bekezdes') || Str::contains($tag_classes, 'pic') || $type === 'labresult') {
                                // Recursively process the inner content
                                if ($node->hasChildren()) {
                                    $this->processSubContent($node, $blocks, $type, $recursionDepth);
                                }
                            }

                            if ($type === 'herbal' && $node->getAttribute('type') === 'curiosity') {
                                $blocks[] = $this->makeBlock('Érdekesség', 'h2');

                                if ($node->hasChildren()) {
                                    $this->processSubContent($node, $blocks, $type, $recursionDepth);
                                }
                            }

                            if (Str::contains($tag_classes, 'instagram-media') && $node->hasAttribute('data-instgrm-permalink')) {
                                // The Embed plugin works with iframes, pass an iframe alternative for processing
                                $url_info = parse_url($node->getAttribute('data-instgrm-permalink'));
                                $instagram_embed_link = rtrim($url_info['scheme'] . '://' . $url_info['host'] . $url_info['path'], '/') . '/embed';
                                $blocks[] = $this->makeBlock('<iframe src="' . $instagram_embed_link . '"></iframe>', 'iframe');
                            }

                            break;
                        case 'section':
                        case 'center':
                        case 'bekezdes':
                            // Recursively process the inner content
                            if ($node->hasChildren()) {
                                $this->processSubContent($node, $blocks, $type, $recursionDepth);
                            }
                            break;
                        case 'iframe':
                            $blocks[] = $this->makeBlock($node->outerHtml(), $tag);
                            break;
                        case 'b':
                        case 'strong':
                        case 'em':
                        case 'i':
                        case 'a':
                        case 'link':
                        case 'u':
                            // Needs outer content to preserve formatting and linking
                            $new_block = $this->tryAppendToLatestParagraph($blocks, $node->outerHtml());
                            if ($new_block) {
                                $blocks[] = $new_block;
                            }
                            break;
                        default:
                            $blocks[] = $this->makeBlock($text, $tag, $type);
                    }
                }
            } else if ($node instanceof TextNode) {
                // Tries to append the pure text content into the latest paragraph
                // Spaces are intentionally not trimmed
                $text = $node->innerHtml();

                if ($text !== '') {
                    $new_block = $this->tryAppendToLatestParagraph($blocks, $text);

                    if ($new_block) {
                        $blocks[] = $new_block;
                    }
                }
            }
        }
        return $blocks;
    }

    /**
     * Helper function to recursively process the sub contents under sections
     * Recursively calls the iteration process and appends the blocks to the output
     * @param HtmlNode $node
     * @param array $blocks
     * @param string|null $type
     * @param int $recursionDepth
     * @return void
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     * @throws UnknownChildTypeException
     */
    private function processSubContent(HtmlNode $node, array &$blocks, ?string $type, int $recursionDepth)
    {
        $sub_dom = new Dom();
        $sub_dom->loadStr($node->innerhtml());
        $content_blocks = $this->iterateNodes($sub_dom, $type, ++$recursionDepth);

        foreach ($content_blocks as $content_block) {
            $blocks[] = $content_block;
        }
    }

    /**
     * Appends the given text to the latest paragraph
     * If the last block is not a paragraph, a new one is created
     * @param array $blocks
     * @param $text
     * @return array|null
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function tryAppendToLatestParagraph(array &$blocks, $text): array|null
    {
        $count = count($blocks);

        if ($count > 0 && ($blocks[$count - 1]['type'] ?? null) === 'paragraph') {
            $this->convertParagraph($blocks[$count - 1], $text, true);
            return null;
        } else {
            return $this->makeBlock($text, 'paragraph');
        }
    }

    /**
     * Makes an EditorJS compatible block from a top-level HTML content
     * Invokes specialized methods for each EditorJS supported block type
     * @param string $text
     * @param string|null $tag
     * @return array
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function makeBlock(string $text, string|null $tag = null, string|null $convert_type = null): array
    {
        $type = match ($tag) {
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6' => 'header',
            'ul', 'ol' => 'list',
            'kep', 'figure', 'img' => 'image',
            'table' => 'table',
            'iframe' => 'embed',
            default => 'paragraph',
        };

        $block = [
            'type' => $type,
            'data' => [],
        ];

        switch ($type) {
            case 'paragraph':
                $this->convertParagraph($block, $text);
                break;
            case 'header':
                $this->convertHeader($block, $tag, $text);
                break;
            case 'list':
                $this->convertList($block, $tag, $text);
                break;
            case 'image':
                if ($tag === 'kep') {
                    $this->convertImage($block, $text);
                } else if ($tag === 'figure') {
                    $this->convertFigure($block, $text);
                } else {
                    $this->convertImage2($block, $text);
                }
                break;
            case 'table':
                $this->convertTable($block, $text);
                break;
            case 'embed':
                $this->convertEmbed($block, $tag, $text);
                break;
        }

        return $block;
    }

    /**
     * Tries to create the given type of special block
     * @param string $text
     * @param string $type
     * @return array|null
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function tryMakeSpecialBlock(string $text, string $type): array|null
    {
        switch ($type) {
            case 'highlighted':
                return $this->convertHighlightedBlock($text);
        }

        return null;
    }

    /**
     * Tries to create a highlighted block from the given text
     * Images are not supported by the editor, currently these tags are skipped
     * @param string $text
     * @return array|null
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function convertHighlightedBlock(string $text): array|null
    {
        $dom = new Dom();
        $dom->loadStr($text);

        if ($dom->hasChildren()) {
            $success = false;

            $block = [];
            $block['type'] = 'paragraph';
            $block['data']['text'] = '<highlighted class="cdx-highlighted">';

            foreach ($dom->getChildren() as $child) {
                $tag_name = $child->tag->name();

                if ($tag_name !== 'img') {
                    $success = true;

                    if (in_array($tag_name, ['b', 'strong', 'em', 'i', 'a', 'link', 'u'])) {
                        $content = $child->outerHtml();
                    }
                    else {
                        $content = $child->innerhtml();
                    }

                    $this->convertParagraph($block, $content, true);
                }
            }

            $block['data']['text'] .= '</highlighted>';

            if ($success) {
                return $block;
            }
        }

        return null;
    }

    /**
     * Converts an HTML paragraph to EditorJS compatible array format
     * <strong>, <em>, <link> and 'url' inner tags are also replaced for compatibility
     * @param array $block
     * @param string $text
     * @param bool $append
     */
    private function convertParagraph(array &$block, string $text, bool $append = false): void
    {
        $search = ['<strong>', '</strong>', '<em>', '</em>', '<link', '</link>', 'url='];
        $replace = ['<b>', '</b>', '<i>', '</i>', '<a', '</a>', 'href='];
        $replaced_text = str_replace($search, $replace, $text);

        if ($append) {
            $block['data']['text'] .= $replaced_text;
        } else {
            $block['data']['text'] = $replaced_text;
        }
    }

    /**
     * Converts an image tag from a paragraph to EditorJS compatible array format
     * <kep> inner tags are also replaced for compatibility
     * @param array $block
     * @param string $text
     */
    private function convertImage(array &$block, string $text): void
    {
        $url = config('constans.hazipatika_media_url');
        $dom = new Dom();
        $dom->loadStr($text);
        $node = $dom->firstChild();

        $image_dir = $node->getAttribute('dir');
        $image_file = $node->getAttribute('file');

        if ($image_dir && $image_file) {
            $image_storage_path = $this->save_image->saveImage($url . rtrim($image_dir, '/') . '/' . $image_file, 'import', true);

            $title = $node->getAttribute('title2');
            $alt = $node->getAttribute('alt2');

            $block['data']['path'] = $image_storage_path;
            $block['data']['caption'] = $title ?? '';
            $block['data']['alt'] = $alt ?? '';
        }
    }

    /**
     * Converts figure tag to EditorJS compatible array format
     * @param array $block
     * @param string $text
     * @return void
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function convertFigure(array &$block, string $text): void
    {
        $dom = new Dom();
        $dom->loadStr($text);

        $images = $dom->find('img');

        if (!$images || count($images) === 0) {
            return;
        }

        $this->convertImage2($block, $images[0]->outerHtml());

        $figcaptions = $dom->find('figcaption');

        if ($figcaptions && count($figcaptions) > 0) {
            $block['data']['caption'] = $figcaptions[0]->innerText();
        }
    }

    /**
     * Converts an image tag from a paragraph to EditorJS compatible array format
     * <kep> inner tags are also replaced for compatibility
     * @param array $block
     * @param string $text
     */
    private function convertImage2(array &$block, string $text): void
    {
        $dom = new Dom();
        $dom->loadStr($text);
        $node = $dom->firstChild();

        $src = $node->getAttribute('src');
        $alt = $node->getAttribute('alt');

        if (!$src) {
            return;
        }

        $image_storage_path = $this->save_image->saveImage($src, 'import', true);

        $block['data']['path'] = $image_storage_path;
        $block['data']['caption'] = '';
        $block['data']['alt'] = $alt ?? '';
    }

    /**
     * Converts an HTML heading to EditorJS compatible array format
     * @param array $block
     * @param string $tag
     * @param string $text
     */
    private function convertHeader(array &$block, string $tag, string $text): void
    {
        $level = match ($tag) {
            'h1' => 1,
            'h2' => 2,
            'h3' => 3,
            'h4' => 4,
            'h5' => 5,
            'h6' => 6,
        };

        $block['data']['level'] = $level;
        $block['data']['text'] = $text;
    }

    /**
     * Converts an HTML list to EditorJS compatible array format
     * @param array $block
     * @param string $tag
     * @param string $text
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     * @throws ContentLengthException
     * @throws CircularException
     * @throws LogicalException
     * @throws StrictException
     */
    private function convertList(array &$block, string $tag, string $text): void
    {
        $style = match ($tag) {
            'ol' => 'ordered',
            'ul' => 'unordered',
        };

        $block['data']['style'] = $style;

        $dom = new Dom();
        $text = str_replace('</li> <li>', '</li><li>', $text);

        $dom->loadStr(trim($text));

        $items = [];

        /** @var AbstractNode $node */
        foreach ($dom->getChildren() as $node) {
            $content = $node->innerhtml();

            // Filter out separator nodes
            if (!empty(trim($content))) {
                $items[] = $content;
            }
        }

        $block['data']['items'] = $items;
    }

    /**
     * Converts an HTML table to EditorJS compatible array format
     * @param array $block
     * @param string $text
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function convertTable(array &$block, string $text): void
    {
        $dom = new Dom();
        $dom->loadStr(trim($text));
        $items = [];
        $separator = '#@@#';

        /** @var AbstractNode $node */
        foreach ($dom->getChildren() as $node) {
            $str = substr(trim(str_replace(['<td>', '</td>'], ['', $separator], $node->innerHtml())), 0, -1 * strlen($separator));
            $str = explode($separator, $str);
            $items[] = $str;
        }

        $block['data']['withHeadings'] = false;
        $block['data']['content'] = $items;
        $block['data']['title'] = null;
        $block['data']['caption'] = null;
        $block['data']['withRowHeading'] = false;
    }

    /**
     * Converts HTML embed codes to EditorJS compatible array format
     * @param array $block
     * @param string $tag
     * @param string $text
     * @return void
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    private function convertEmbed(array &$block, string $tag, string $text): void
    {
        if ($tag === 'iframe') {
            $dom = new Dom();
            $dom->loadStr($text);
            $iframe = $dom->root->firstChild();
            $src = $iframe->getAttribute('src');
            $url_info = parse_url($src);

            if (is_array($url_info)) {
                switch ($url_info['host']) {
                    case 'content.jwplatform.com':
                    case 'cdn.jwplayer.com':
                        $block['data']['service'] = 'jw';
                        $block['data']['width'] = 500;
                        $block['data']['height'] = 281;
                        break;
                    case 'youtube.com':
                    case 'www.youtube.com':
                        $block['data']['service'] = 'youtube';
                        $block['data']['width'] = 560;
                        $block['data']['height'] = 315;
                        break;
                    case 'instagram.com':
                    case 'www.instagram.com':
                        $block['data']['service'] = 'instagram';
                        $block['data']['width'] = 500;
                        $block['data']['height'] = 832;
                        break;
                    case 'facebook.com':
                    case 'www.facebook.com':
                        if ($url_info['path'] === '/plugins/post.php') {
                            $block['data']['service'] = 'facebook';
                            $block['data']['width'] = 500;
                            $block['data']['height'] = 506;
                        }
                        break;
                }

                $block['data']['source'] = $src;
                $block['data']['embed'] = $src;
                $block['data']['caption'] = null;

                if (!isset($block['data']['service'])) {
                    $block = $this->rawBlockFallback('<iframe src="'. $src .'" width="500px" height="300px"></iframe>');
                }
            }
        }
    }

    /**
     * Creates a raw block with the given text for fallback
     * @param string $text
     * @return array
     */
    private function rawBlockFallback(string $text): array
    {
        $block = [];
        $block['type'] = 'raw';
        $block['data']['html'] = $text;
        return $block;
    }
}
