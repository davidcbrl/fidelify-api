<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Fidelify\Api\Middlewares\Auth\AuthMiddleware;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ApplicationInterface
{
    public static function create(): self;
    public function handle(ServerRequestInterface $request): ResponseInterface;
}

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

        $router->map('GET', '/', function (): ResponseInterface {
            return new Response(json_encode([
                'title'   => 'Fidelify API',
                'version' => file_get_contents('VERSION'),
            ]), 200);
        });

        $router->group('/auth', function (RouteGroup $route) {
            $route->map('POST', '/signup', [AuthController::class, 'signup']);
            $route->map('POST', '/login', [AuthController::class, 'login']);
            $route->map('POST', '/password', [AuthController::class, 'password']);
        });

        $router->group('/user', function (RouteGroup $route) {
            $route->map('GET', '/list', [UserController::class, 'getAll']);
            $route->map('GET', '/{id}', [UserController::class, 'getOne']);
            $route->map('POST', '/{id}', [UserController::class, 'create']);
            $route->map('PUT', '/{id}', [UserController::class, 'update']);
        })->middleware(new AuthMiddleware());

        // $router->group('/company', function (RouteGroup $route) {
        //     $route->map('GET', '/list', [UserController::class, 'getAll']);
        //     $route->map('GET', '/{id}', [UserController::class, 'getOne']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(new AuthMiddleware());

        // $router->group('/category', function (RouteGroup $route) {
        //     $route->map('GET', '/list', [UserController::class, 'getAll']);
        //     $route->map('GET', '/{id}', [UserController::class, 'getOne']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(new AuthMiddleware());

        // $router->group('/product', function (RouteGroup $route) {
        //     $route->map('GET', '/list', [UserController::class, 'getAll']);
        //     $route->map('GET', '/{id}', [UserController::class, 'getOne']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(new AuthMiddleware());

        // $router->group('/fidelity', function (RouteGroup $route) {
        //     $route->map('GET', '/list', [UserController::class, 'getAll']);
        //     $route->map('GET', '/{id}', [UserController::class, 'getOne']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        //     $route->map('POST', '/{id}/checkpoint', [UserController::class, 'checkpoint']);
        // })->middleware(new AuthMiddleware());

        // $router->group('/dashboard', function (RouteGroup $route) {
        //     $route->map('GET', '/fidelity/list', [UserController::class, 'getAll']);
        //     $route->map('GET', '/company/list', [UserController::class, 'getAll']);
        // })->middleware(new AuthMiddleware());

        return $router->dispatch($request);
    }
}
