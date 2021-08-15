<?php

namespace App;

use Closure;
use App\Page;
use DOMDocument;
use App\Contracts\Url;
use GuzzleHttp\ClientInterface;
use App\Contracts\Page as PageInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Stopwatch\Stopwatch;
use App\Contracts\Crawler as CrawlerInterface;

class Crawler implements CrawlerInterface
{
    /**
     * Maximum number of pages to crawl.
     *
     * @var int
     */
    private int $maxNumberOfPages = 25;

    /**
     * Number of crawled pages.
     *
     * @var int
     */
    private int $numberOfCrawledPages = 0;

    /**
     * Array of crawled pages.
     *
     * @var array
     */
    private array $crawledPages = [];

    /**
     * Constructor.
     *
     * @param \GuzzleHttp\ClientInterface $client HTTP Client used to request pages.
     */
    public function __construct(
        private ClientInterface $client
    ) {}

    /**
     * Set the maximum number of pages to crawl.
     *
     * @param int $number
     *
     * @return \App\Contracts\Crawler
     */
    public function withMaxNumberOfPages(int $number): CrawlerInterface
    {
        $this->maxNumberOfPages = $number;

        return $this;
    }

    /**
     * Start crawling using a given seed URL.
     *
     * @param \App\Contracts\Url $url Seed URL.
     *
     * @return void
     */
    public function crawl(Url $url): void
    {
        if ($this->numberOfCrawledPages < $this->maxNumberOfPages && $this->hasNotBeenCrawled($url)) {
            $page = $this->getPage($url);
            $this->addToCrawledPages($page);

            foreach ($page->internalLinks() as $url) {
                $this->crawl($url);
            }
        }
    }

    /**
     * Get crawled pages.
     *
     * @return array
     */
    public function crawledPages(): array
    {
        return $this->crawledPages;
    }

    /**
     * Determine if a URL has been crawled.
     *
     * @param \App\Contracts\Url $url URL to check.
     *
     * @return bool
     */
    private function hasBeenCrawled(Url $url): bool
    {
        return isset($this->crawledPages[(string) $url]);
    }

    /**
     * Determine if a URL has not been crawled.
     *
     * @param \App\Contracts\Url $url URL to check.
     *
     * @return bool
     */
    private function hasNotBeenCrawled(Url $url): bool
    {
        return !$this->hasBeenCrawled($url);
    }

    /**
     * Add a page to the crawled pages array.
     *
     * @param \App\Contracts\Page $page Page to add.
     *
     * @return void
     */
    private function addToCrawledPages(PageInterface $page): void
    {
        $this->crawledPages[(string) $page->url()] = $page;
        $this->numberOfCrawledPages++;
    }

    /**
     * Get the page of a given URL.
     *
     * @param \App\Contracts\Url $url Page URL.
     *
     * @return \App\Contracts\Page
     */
    private function getPage(Url $url): PageInterface
    {
        $response = null;

        $loadTime = $this->profile(function () use (&$response, $url) {
            try {
                $response = $this->client->request('GET', (string) $url);
            } catch (RequestException $exception) {
                $response = $exception->getResponse();
            }
        });

        $statusCode = $response->getStatusCode();

        $content = new DOMDocument();
        @$content->loadHtml($response->getBody()->getContents());

        // Remove script tags
        foreach(iterator_to_array($content->getElementsByTagName('script')) as $node) {
            $node->parentNode->removeChild($node);
        };

        return new Page($url, $statusCode, $loadTime, $content);
    }

    /**
     * Profile and return the time taken to execute a given callback.
     *
     * @param \Closure $callback Callback to measure the time of.
     *
     * @return mixed
     */
    private function profile(Closure $callback): mixed
    {
        $stopwatch = new Stopwatch(true);
        $stopwatch->start('executionTime');

        $callback();

        return $stopwatch->stop('executionTime')->getDuration();
    }
}
