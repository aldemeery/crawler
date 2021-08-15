<?php

use App\Url;
use App\Crawler;
use GuzzleHttp\Client;

require_once __DIR__ . '/bootstrap.php';

$seed = $_GET['url'] ?? '';

$crawler = new Crawler(new Client());
$crawler->withMaxNumberOfPages(5)->crawl(new Url($seed));
$output = $crawler->crawledPages();

// 'metrics' is defined in helpers.php
$metrics = metrics($output);

header('Content-Type: application/json');
echo json_encode($metrics);
