<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Fidelify\Api\Application;
use Ilex\SwoolePsr7\SwooleResponseConverter;
use Ilex\SwoolePsr7\SwooleServerRequestConverter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$app = Application::create();

$psr17Factory = new Psr17Factory;
$requestConverter = new SwooleServerRequestConverter(
    $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
);

$server = new Server('0.0.0.0', 8003);

$server->on('start', function () {
    echo '[fidelify-api] Server started at http://127.0.0.1:8003' . PHP_EOL;
});

$server->on('request', function (Request $request, Response $response) use ($app, $requestConverter) {
    $psr7Request = $requestConverter->createFromSwoole($request);
    $psr7Response = $app->handle($psr7Request);
    $converter = new SwooleResponseConverter($response);
    $converter->send($psr7Response);
});

$server->start();
