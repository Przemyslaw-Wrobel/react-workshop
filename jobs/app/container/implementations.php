<?php

/** @var \Pimple\Container $container */

/**
 * Implementation aware services
 */
$container['jobs.provider'] = fn() => $container['jobs.provider.react'];

$container['query.executor.factory'] = fn() => $container['query.executor.factory.pdo'];

$container['http.client'] = fn() => $container['http.client.curl'];

$container['stats.storage'] = fn() => $container['stats.storage.predis'];

/**
 * Synchronous implementations
 */
$container['pdo'] = function () {
    $pdo = new PDO('mysql:dbname=app;host=mysql', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
};

$container['jobs.provider.pdo'] = fn() => new \Jobs\Adapters\Sync\PDOJobProvider($container['pdo']);

$container['query.executor.factory.pdo'] = fn() => new \Jobs\Adapters\Sync\PDOQueryExecutorFactory($container['pdo']);

$container['http.client.curl'] = fn() => new \Jobs\Adapters\Sync\CURLHttpClient();

$container['stats.storage.predis'] = fn() => new \Jobs\Adapters\Sync\PredisStatsStorage(new \Predis\Client('tcp://redis:6379'));

/**
 * React implementations
 */
$container['react.mysql'] = fn() => (new \React\MySQL\Factory())->createLazyConnection('root:root@mysql/app');

$container['react.redis'] = fn() => (new \Clue\React\Redis\Factory())->createLazyClient('redis:6379');

$container['jobs.provider.react'] = fn() => new \Jobs\Adapters\React\ReactJobProvider($container['react.mysql']);
