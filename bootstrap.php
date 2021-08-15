<?php

use Whoops\Run;
use Whoops\Handler\JsonResponseHandler;

require_once __DIR__ . '/vendor/autoload.php';

$run = new Run();
$jsonHandler = new JsonResponseHandler();
$jsonHandler->setJsonApi(true);
$run->pushHandler($jsonHandler);
$run->register();
