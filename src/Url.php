<?php

namespace App;

use function Sabre\Uri\parse;
use App\Contracts\Url as UrlInterface;

class Url implements UrlInterface
{
    /**
     * Url scheme.
     *
     * @var string
     */
    private string $scheme;

    /**
     * Url host.
     *
     * @var string
     */
    private string $host;

    /**
     * Url path.
     *
     * @var string
     */
    private string $path;

    /**
     * Constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $parsed = parse($url);
        $this->scheme = $parsed['scheme'] ?? 'https';
        $this->host = trim($parsed['host'], '/');
        $this->path = trim($parsed['path'] ?? '', '/');
    }

    /**
     * Get the scheme of this url.
     *
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Get the host of this url.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get the path of this url.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get the string representation of this url.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            "%s://%s/%s",
            $this->scheme,
            $this->host,
            $this->path
        );
    }
}
