<?php

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$server = new Server('0.0.0.0', 8003);

$server->on('start', function (Server $server) {
    echo '[fidelify-api] Server started at http://127.0.0.1:8003' . PHP_EOL;
});

$server->on('request', function (Request $request, Response $response) {
    $response->end('<h1>Hello World!</h1>');
});

$server->start();
