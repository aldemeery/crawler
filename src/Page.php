<?php

namespace App;

use App\Url;
use DOMDocument;
use function Sabre\Uri\resolve;
use App\Contracts\Page as PageInterface;

class Page implements PageInterface
{
    /**
     * The title of the page.
     *
     * @var string
     */
    private string $title;

    /**
     * Number of words in the body of this page.
     *
     * @var int
     */
    private int $wordCount;

    /**
     * Internal links in this page.
     *
     * @var array
     */
    private array $internalLinks = [];

    /**
     * External links in this page.
     *
     * @var array
     */
    private array $externalLinks = [];

    /**
     * Images in this page.
     *
     * @var array
     */
    private array $images = [];

    /**
     * Constructor.
     *
     * @param \App\Contracts\Url $url        The URL of the page.
     * @param int                $statusCode The status code of the page.
     * @param float              $loadTime   The time consumed to load tha page.
     * @param \DOMDocument       $content    The HTML content of the page.
     */
    public function __construct(
        private Url $url,
        private int $statusCode,
        private float $loadTime,
        DOMDocument $content
    ) {
        $this->parse($content);
    }

    /**
     * Get the URL of this page.
     *
     * @return \App\Contracts\Url
     */
    public function url(): Url
    {
        return $this->url;
    }

    /**
     * Get the status code of this page.
     *
     * @return int
     */
    public function statusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the time consumed to load this page.
     *
     * @return float
     */
    public function loadTime(): float
    {
        return $this->loadTime;
    }

    /**
     * Get the title of this page.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get the number of words in the body of this page.
     *
     * @return int
     */
    public function wordCount(): int
    {
        return $this->wordCount;
    }

    /**
     * Get the internal links in this page.
     *
     * @return array
     */
    public function internalLinks(): array
    {
        return $this->internalLinks;
    }

    /**
     * Get the external links in this page.
     *
     * @return array
     */
    public function externalLinks(): array
    {
        return $this->externalLinks;
    }

    /**
     * Get the images in this page.
     *
     * @return array
     */
    public function images(): array
    {
        return $this->images;
    }

    /**
     * Extract the attributes of this page from its content.
     *
     * @param \DOMDocument $content Page content.
     *
     * @return void
     */
    private function parse(DOMDocument $content): void
    {
        $this->parseTitle($content);
        $this->parseWordCount($content);
        $this->parseLinks($content);
        $this->parseImages($content);
    }

    /**
     * Parse the page title from its content.
     *
     * @param \DOMDocument $content Page content.
     *
     * @return void
     */
    private function parseTitle(DOMDocument $content): void
    {
        $this->title = $content->getElementsByTagName('title')->item(0)?->nodeValue ?? '';
    }

    /**
     * Count the number of words of the page.
     *
     * @param \DOMDocument $content Page content.
     *
     * @return void
     */
    private function parseWordCount(DOMDocument $content): void
    {
        $body = $content->getElementsByTagName('body')->item(0) ?? '';
        $this->wordCount = str_word_count($body->textContent);
    }

    /**
     * Extract the internal and external links from the content.
     *
     * @param \DOMDocument $content Page content.
     *
     * @return void
     */
    private function parseLinks(DOMDocument $content): void
    {
        $links = $content->getElementsByTagName('a');

        for ($i = 0; $i < $links->count(); $i++) {
            $link = $links->item($i)->attributes->getNamedItem('href')?->value ?? '';

            $url = new Url($this->normalizeUrl($link));

            if (str_starts_with((string) $url, 'http')) {
                if (strtolower($url->getHost()) === strtolower($this->url()->getHost())) {
                    $this->internalLinks[(string) $url] = $url;
                } else {
                    $this->externalLinks[(string) $url] = $url;
                }
            }
        }
    }

    /**
     * Extract the images from the content.
     *
     * @param \DOMDocument $content Page content.
     *
     * @return void
     */
    private function parseImages(DOMDocument $content): void
    {
        $images = $content->getElementsByTagName('img');

        for ($i=0; $i < $images->count(); $i++) {
            $image = $images->item($i)->attributes->getNamedItem('src')?->value ?? '';

            $image = $this->normalizeUrl($image);

            $this->images[$image] = $image;
        }
    }

    /**
     * Normalize a given URL string.
     *
     * @param string $url URL to normalize.
     *
     * @return string
     */
    private function normalizeUrl(string $url): string
    {
        $base = sprintf("%s://%s/%s/", $this->url()->getScheme(), $this->url()->getHost(), $this->url()->getPath());
        $url = resolve($base, $url);

        return $url;
    }
}
