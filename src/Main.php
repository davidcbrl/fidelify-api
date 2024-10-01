<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Ilex\SwoolePsr7\SwooleResponseConverter;
use Ilex\SwoolePsr7\SwooleServerRequestConverter;
use Nyholm\Psr7\Factory\Psr17Factory;

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class Main
{
    public function __construct(
        private Server $server
    ) {
        $this->server = $server;
    }

    public static function create(): self
    {
        $serverHost = is_string(getenv('SERVER_HOST')) ? getenv('SERVER_HOST') : '0.0.0.0';
        $serverPort = is_numeric(getenv('SERVER_PORT')) ? getenv('SERVER_PORT') : 8003;

        $server = new Server($serverHost, $serverPort);

        return new static($server);
    }

    public function run(): void
    {
        $psr17Factory = new Psr17Factory;
        $requestConverter = new SwooleServerRequestConverter(
            $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
        );

        $routes = Routes::create();

        $this->server->on('start', function (): void {
            echo '[fidelify-api] server started at http://127.0.0.1:8003' . PHP_EOL;
        });

        $this->server->on('request', function (Request $request, Response $response) use ($routes, $requestConverter) : void {
            $psr7Request = $requestConverter->createFromSwoole($request);
            $psr7Response = $routes->handle($psr7Request);
            $converter = new SwooleResponseConverter($response);
            $converter->send($psr7Response);
            echo "[fidelify-api] {$psr7Response->getStatusCode()} {$psr7Response->getReasonPhrase()} {$psr7Response->getBody()}" . PHP_EOL;
        });

        $this->server->start();
    }
}
