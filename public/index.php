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

$psr17Factory = new Psr17Factory();
$requestConverter = new SwooleServerRequestConverter(
    serverRequestFactory: $psr17Factory,
    uriFactory: $psr17Factory,
    uploadedFileFactory: $psr17Factory,
    streamFactory: $psr17Factory,
);

$server = new Server(host: '0.0.0.0', port: 8003);

$server->on('start', function () {
    echo '[fidelify-api] Server started at http://127.0.0.1:8003' . PHP_EOL;
});

$server->on('request', function (Request $request, Response $response) use ($app, $requestConverter) {
    echo "[fidelify-api] Request: {$request->server['request_method']} {$request->server['request_uri']}" . PHP_EOL;
    $psr7Request = $requestConverter->createFromSwoole(swooleRequest: $request);
    $psr7Response = $app->handle(request: $psr7Request);
    $converter = new SwooleResponseConverter(response: $response);
    $converter->send(response: $psr7Response);
    echo "[fidelify-api] Response: {$psr7Response->getStatusCode()} {$psr7Response->getReasonPhrase()} {$psr7Response->getBody()}" . PHP_EOL;
});

$server->start();
