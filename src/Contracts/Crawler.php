<?php

namespace App\Contracts;

use App\Contracts\Url;

interface Crawler
{
    /**
     * Set the maximum number of pages to crawl.
     *
     * @param int $number
     *
     * @return \App\Contracts\Crawler
     */
    public function withMaxNumberOfPages(int $number): Crawler;

    /**
     * Start crawling using a given seed URL.
     *
     * @param \App\Contracts\Url $url Seed URL.
     *
     * @return void
     */
    public function crawl(Url $url): void;

    /**
     * Get crawled pages.
     *
     * @return array
     */
    public function crawledPages(): array;
}
