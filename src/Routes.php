<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Fidelify\Api\Controllers\Auth\AuthController;
use Fidelify\Api\Controllers\User\UserController;

use League\Route\RouteGroup;
use League\Route\Router;
use ReflectionClass;

interface RoutesInterface
{
    public static function load($endpoint, Router &$router);
}

class Routes implements RoutesInterface
{
    public static function load($endpoint, Router &$router): void
    {
        $controller = new ReflectionClass($endpoint);
        $controllerAttrs = $controller->getAttributes(Endpoint::class)[0];
        $controllerMeths = $controller->getMethods();

        if (empty($controllerAttrs) || empty($controllerMeths)) {
            return;
        }

        $router->group($controllerAttrs->base, function (RouteGroup $route) use ($controller, $controllerMeths) {
            foreach ($controllerMeths as $method) {
                $methodAttrs = $method->getAttributes(Endpoint::class)[0];

                $route->map(
                    $methodAttrs->method,
                    $methodAttrs->path,
                    [$controller, $method->getName()]
                );
            }
        });
    }
}
