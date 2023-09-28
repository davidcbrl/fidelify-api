<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Fidelify\Api\Modules\Auth\Controller\AuthController;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ResponseFactory;
use League\Container\Container;
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
    CONST ENDPOINTS = [
        AuthController::class,
    ];

    public static function create(): self
    {
        return new static();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $responseFactory = new ResponseFactory();
        $strategy = new JsonStrategy(responseFactory: $responseFactory);
        $container = new Container();
        $router = new Router();

        $router->map('GET', '/', function (): ResponseInterface {
            return new Response(
                status: 200,
                headers: [
                    'Content-Type' => 'application/json',
                ],
                body: json_encode([
                    'title'   => 'Fidelify API',
                    'version' => '0.0.1',
                ]),
            );
        });

        foreach (self::ENDPOINTS as $endpoint) {
            $controller = new \ReflectionClass($endpoint);

            $controllerAttr = $controller->getAttributes(name: Route::class)[0];
            $controllerArgs = $controllerAttr->getArguments();
            $controllerMeths = $controller->getMethods();

            if (empty($controllerAttr) || empty($controllerArgs) || empty($controllerMeths)) {
                continue;
            }

            $router->group(
                prefix: $controllerArgs['group'],
                group: function (RouteGroup $route) use ($container, $endpoint, $controllerMeths) {
                    foreach ($controllerMeths as $method) {
                        if ($method->class !== $endpoint) {
                            continue;
                        }

                        if ($method->isConstructor()) {
                            $constructorParams = $method->getParameters();

                            if (!empty($constructorParams)) {
                                foreach ($constructorParams as $param) {
                                    $container->add($endpoint)->addArgument($param->getType()->getName());
                                }
                            }
                            continue;
                        }

                        $methodAttr = $method->getAttributes(name: Route::class)[0];
                        $methodArgs = $methodAttr->getArguments();

                        if (empty($methodAttr) || empty($methodArgs)) {
                            continue;
                        }

                        $route->map(
                            method: $methodArgs['method'],
                            path: $methodArgs['path'],
                            handler: [$endpoint, $method->getName()],
                        );
                    }
                }
            );
        }

        $strategy->setContainer(container: $container);
        $router->setStrategy(strategy: $strategy);

        // $router->group('/user', function (RouteGroup $route) {
        //     $route->map('GET', '/list', [UserController::class, 'getAll']);
        //     $route->map('GET', '/{id}', [UserController::class, 'getOne']);
        //     $route->map('POST', '/{id}', [UserController::class, 'create']);
        //     $route->map('PUT', '/{id}', [UserController::class, 'update']);
        // })->middleware(new AuthMiddleware());

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

        return $router->handle(request: $request);
    }
}
