<?php

require_once __DIR__ . '/vendor/autoload.php';

$handler = function (\Psr\Http\Message\ServerRequestInterface $request) {
    $params = $request->getQueryParams();

    $id = (int)$params['id'];
    $interval = (int)$params['interval'];

    var_dump($id, $interval);

    return new React\Promise\Promise(function ($resolve) use ($interval) {
        $response = new \React\Http\Message\Response(200, ['Content-type' => 'text/plain'], 'Ok');

        \React\EventLoop\Loop::addTimer($interval / 2, fn() => $resolve($response));
    });
};

$http = new \React\Http\HttpServer($handler);

$socket = new \React\Socket\SocketServer('0.0.0.0:80');

$http->listen($socket);
