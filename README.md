# Crawler

* [About](#about)
* [How it works](#how-it-works)
* [Limitations](#limitations)
* [Libraries Used](#libraries-used)

### [Demo](https://aldemeery.github.io/crawler-front/)

---

## About

This is a simple crawler built in native PHP.

Providing a seed URL, and a maximum number of pages to crawl, the crawler starts crawling internal pages, collecting load time, status code, title, words count, internal links, external links, and images of each internal page.

![Demo GIF](/recording.gif)


> NOTE:
>
> This project does not demonstrate how to build/provide an API, nor it does demonstrate how to use MVC framewords (e.g. Phalcon, Laravel ...etc) to do so. Instead, an old-fashioned PHP script in the `api.php` file is used to provide an endpoint for demo purposes only.
>
> This project, however, demonstrats how to use native PHP to crawl a website to collect data.


## How it works

1. A new instance of `Crawler::class` implementation is instantiated, along with a provided HTTP client instance that will be used to request URLs.
2. A seed URL is given to the crawler instance to start crawling.
3. If the URL is not already crawled, the URL is requested using the HTTP client, and a new instance of `DOMDocument::class` is instantiated using the retured response.
4. A new instance of `Page::class` implementation is then instantiated using the dom document instance, which is used to extract the data needed to instantiate the page instance.
5. We now have a `Page::class` instance which represents a crawled page with all of its information (e.g. load time, status code, title, ...etc)
6. The page instance is added to the array that holds all crawled pages.
7. Internal URLs in the newly crawled page, are then given to the crawler to start the cycle again.
8. When all URLs, or the maximum number of pages has been crawled, the array of crawled pages is returned.

![Flow Diagram](/flow.jpg);

## Limitations

Since this project is a simple demonstration, some things have been ignored, some of them are:

1. Ability to respect/ignore `robots.txt` files
2. Ability to configure the HTTP client (.e.g. Setting user-agent, Setting cookies ...etc)
3. Ability to set delays between requests to work around rate-limiting
4. Ability to crawl Javascript-rendered pages (e.g. SPAs)
5. URLs validation.
6. Handling unreached domain names (currently return 500 responses)
7. Building a better, responsive frontend

## Libraries Used

- [sabre/uri](https://packagist.org/packages/sabre/uri) Used for resolving & normalizing URLs.
- [symfony/stopwatch](https://packagist.org/packages/symfony/stopwatch) Used for timing requests.
- [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle) HTTP client used for sending requests.
- [filp/whoops](https://packagist.org/packages/filp/whoops) Used for exception handling.
- [DOMDocument](https://www.php.net/manual/en/class.domdocument.php) Used for manipulating HTML.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
