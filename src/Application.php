<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application implements ApplicationInterface
{
    public static function create(): self
    {
        return new static();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $responseFactory = new ResponseFactory();
        $strategy = new JsonStrategy($responseFactory);
        $router = new Router();
        $router->setStrategy($strategy);

        $router->map('GET', '/', function () use ($request): ResponseInterface {
            $response = new Response();
            $response->getBody()->write(json_encode([
                'title'   => 'Fidelify API',
                'version' => 1,
            ]));
            return $response;
        });

        return $router->dispatch($request);
    }
}
