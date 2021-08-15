<?php

if (!function_exists('metrics')) {
    function metrics(array $output): array
    {
        $numberOfPagesCrawled = count($output);

        $internalLinks = [];
        $externalLinks = [];
        $images = [];
        $pages = [];
        $totalLoad = 0;
        $totalWordCount = 0;
        $totalTitleLength = 0;

        foreach ($output as $url => $page) {
            $internalLinks = array_merge($internalLinks, [$url], array_keys($page->internalLinks()));
            $externalLinks = array_merge($externalLinks, array_keys($page->externalLinks()));
            $images = array_merge($images, array_keys($page->images()));
            $totalLoad += $page->loadTime();
            $totalWordCount += $page->wordCount();
            $totalTitleLength += strlen($page->title());
            $pages[] = [
                'url' => (string) $page->url(),
                'statusCode' => $page->statusCode(),
                'loadTime' => $page->loadTime(),
            ];
        }

        $numberOfInternalLinks = count(array_unique($internalLinks));
        $numberOfExternalLinks = count(array_unique($externalLinks));
        $numberOfImages = count(array_unique($images));
        $avgLoad = round($totalLoad / $numberOfPagesCrawled, 2);
        $avgWordCount = round($totalWordCount / $numberOfPagesCrawled, 2);
        $avgTitleLength = round($totalTitleLength / $numberOfPagesCrawled);

        return compact(
            'numberOfPagesCrawled',
            'numberOfInternalLinks',
            'numberOfExternalLinks',
            'numberOfImages',
            'avgLoad',
            'avgWordCount',
            'avgTitleLength',
            'pages',
        );
    }
}
