<?php

require_once './vendor/autoload.php';
require_once './container/container.php';

/** @var \Jobs\App $app */
$app = $container['app'];

$app->run();
