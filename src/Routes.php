<?php

declare(strict_types=1);

namespace Fidelify\Api;

const DS = DIRECTORY_SEPARATOR;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ResponseFactory;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Fidelify\Api\Modules\Auth\Controllers\AuthController;
use Fidelify\Api\Modules\Auth\Middlewares\AuthMiddleware;

class Routes
{
    public function __construct(
        private Router $router,
    ) {}

    public static function create(): self
    {
        $responseFactory = new ResponseFactory();
        $strategy = new JsonStrategy(responseFactory: $responseFactory);
        $router = new Router();
        $router->setStrategy(strategy: $strategy);

        return new self(router: $router);
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        $this->router->map(method: 'GET', path: '/', handler: function (): ResponseInterface {
            return new JsonResponse(data: [
                'title'   => 'Fidelify API',
                'version' => trim(string: file_get_contents(filename: dirname(path: __DIR__) . DS . 'VERSION')),
            ], status: 200);
        });

        $this->router->group(prefix: '/auth', group: function (RouteGroup $route): void {
            $route->map(method: 'POST', path: '/signup', handler: [AuthController::class, 'signup']);
            $route->map(method: 'POST', path: '/signin', handler: [AuthController::class, 'signin']);
        });

        // $this->router->group(prefix: '/user', group: function (RouteGroup $route): void {
        //     $route->map(method: 'GET', path: '/list', handler: [UserController::class, 'list']);
        //     $route->map(method: 'GET', path: '/{id}', handler: [UserController::class, 'get']);
        //     $route->map(method: 'POST', path: '/{id}', handler: [UserController::class, 'create']);
        //     $route->map(method: 'PUT', path: '/{id}', handler: [UserController::class, 'update']);
        // })->middleware(middleware: new AuthMiddleware());

        // $this->router->group('/company', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(middleware: new AuthMiddleware());

        // $this->router->group('/category', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(middleware: new AuthMiddleware());

        // $this->router->group('/product', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(middleware: new AuthMiddleware());

        // $this->router->group('/fidelity', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        //     $route->map('POST', '/{id}/checkpoint', [UserController::class, 'checkpoint']);
        // })->middleware(middleware: new AuthMiddleware());

        // $this->router->group('/dashboard', function (RouteGroup $route): void {
        //     $route->map('GET', '/fidelity/list', [UserController::class, 'list']);
        //     $route->map('GET', '/company/list', [UserController::class, 'list']);
        // })->middleware(middleware: new AuthMiddleware());

        return $this->router->dispatch($request);
    }
}
