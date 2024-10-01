<?php

declare(strict_types=1);

namespace Fidelify\Api;

const DS = DIRECTORY_SEPARATOR;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Fidelify\Api\Modules\Auth\Controllers\AuthController;
use Fidelify\Api\Modules\User\Controllers\UserController;
use Fidelify\Api\Modules\Auth\Middlewares\AuthMiddleware;

class Routes
{
    public function __construct(
        private Router $router
    ) {
        $this->router = $router;
    }

    public static function create(): self
    {
        $responseFactory = new ResponseFactory();
        $strategy = new JsonStrategy($responseFactory);
        $router = new Router();
        $router->setStrategy($strategy);

        return new static($router);
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        $this->router->map('GET', '/', function (): ResponseInterface {
            return new Response(json_encode([
                'title'   => 'Fidelify API',
                'version' => file_get_contents(dirname(__DIR__) . DS . 'VERSION'),
            ]), 200);
        });

        $this->router->group('/auth', function (RouteGroup $route): void {
            $route->map('POST', '/signup', [AuthController::class, 'signup']);
            $route->map('POST', '/signin', [AuthController::class, 'signin']);
            $route->map('POST', '/signout', [AuthController::class, 'signout']);
            $route->map('POST', '/reset', [AuthController::class, 'reset']);
        });

        $this->router->group('/user', function (RouteGroup $route): void {
            $route->map('GET', '/list', [UserController::class, 'list']);
            $route->map('GET', '/{id}', [UserController::class, 'get']);
            $route->map('POST', '/{id}', [UserController::class, 'create']);
            $route->map('PUT', '/{id}', [UserController::class, 'update']);
        })->middleware(new AuthMiddleware());

        // $this->router->group('/company', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(new AuthMiddleware());

        // $this->router->group('/category', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(new AuthMiddleware());

        // $this->router->group('/product', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(new AuthMiddleware());

        // $this->router->group('/fidelity', function (RouteGroup $route): void {
        //     $route->map('GET', '/list', [UserController::class, 'list']);
        //     $route->map('GET', '/{id}', [UserController::class, 'get']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        //     $route->map('POST', '/{id}/checkpoint', [UserController::class, 'checkpoint']);
        // })->middleware(new AuthMiddleware());

        // $this->router->group('/dashboard', function (RouteGroup $route): void {
        //     $route->map('GET', '/fidelity/list', [UserController::class, 'list']);
        //     $route->map('GET', '/company/list', [UserController::class, 'list']);
        // })->middleware(new AuthMiddleware());

        return $this->router->dispatch($request);
    }
}
