<?php

namespace App\Contracts;

use Stringable;

interface Url extends Stringable
{
    /**
     * Get the scheme of this url.
     *
     * @return string
     */
    public function getScheme(): string;

    /**
     * Get the host of this url.
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Get the path of this url.
     *
     * @return string
     */
    public function getPath(): string;
}
