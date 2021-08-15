<?php

namespace App\Contracts;

use App\Contracts\Url;

interface Page
{
    /**
     * Get the URL of this page.
     *
     * @return \App\Contracts\Url
     */
    public function url(): Url;

    /**
     * Get the status code of this page.
     *
     * @return int
     */
    public function statusCode(): int;

    /**
     * Get the time consumed to load this page.
     *
     * @return float
     */
    public function loadTime(): float;

    /**
     * Get the title of this page.
     *
     * @return string
     */
    public function title(): string;

    /**
     * Get the number of words in the body of this page.
     *
     * @return int
     */
    public function wordCount(): int;

    /**
     * Get the internal links in this page.
     *
     * @return array
     */
    public function internalLinks(): array;

    /**
     * Get the external links in this page.
     *
     * @return array
     */
    public function externalLinks(): array;

    /**
     * Get the images in this page.
     *
     * @return array
     */
    public function images(): array;
}
