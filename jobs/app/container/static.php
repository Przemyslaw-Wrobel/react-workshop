<?php

function withLogger(\Psr\Log\LoggerAwareInterface $object): \Psr\Log\LoggerAwareInterface {
    global $container;
    $object->setLogger($container['log']);
    return $object;
}

/**
 * Static DI
 */
$container['loop'] = fn() => \React\EventLoop\Loop::get();

$container['log'] = function () {
    $logger = new Monolog\Logger('app');
    $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));

    return $logger;
};

$container['stats.collector'] = fn() => new \Jobs\Stats\Collector($container['stats.storage']);

$container['http.reporter'] = fn() => new \Jobs\HttpReporter\HttpReporter($container['http.client']);

$container['scheduler'] = fn() => withLogger(
    new \Jobs\Scheduler($container['loop'])
);

$container['executor.factory'] = fn() => withLogger(
    new \Jobs\Executor\ExecutorFactory(
        $container['query.executor.factory'],
        $container['http.reporter'],
        $container['stats.collector']
    )
);

$container['executor'] = fn() => $container['executor.factory']->create();

$container['app'] = fn() => withLogger(
    new \Jobs\App(
        $container['jobs.provider'],
        $container['scheduler'],
        $container['executor']
    )
);
