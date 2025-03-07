<?php

declare(strict_types=1);

namespace Fidelify\Api;

use League\Container\Container;

class Provider
{
    public function __construct(
        private Container $container,
    ) {}

    public static function create(): Container
    {
        $container = new Container();

        $container->add(id: \Fidelify\Api\Modules\Util\Services\ValidationService::class)
            ->addArgument(arg: \Fidelify\Api\Adapters\Validation\IlluminateAdapter::create());

        $container->add(id: \Fidelify\Api\Modules\Auth\Middlewares\AuthMiddleware::class)
            ->addArgument(arg: \Fidelify\Api\Adapters\Token\JwtAdapter::create());
        $container->add(id: \Fidelify\Api\Modules\Auth\Repositories\AuthRepository::class)
            ->addArgument(arg: \Fidelify\Api\Adapters\Database\MysqlAdapter::create())
            ->addArgument(arg: \Fidelify\Api\Adapters\Token\JwtAdapter::create());
        $container->add(id: \Fidelify\Api\Modules\Auth\Services\AuthService::class)
            ->addArgument(arg: \Fidelify\Api\Modules\Auth\Repositories\AuthRepository::class);
        $container->add(id: \Fidelify\Api\Modules\Auth\Controllers\AuthController::class)
            ->addArgument(arg: \Fidelify\Api\Modules\Util\Services\ValidationService::class)
            ->addArgument(arg: \Fidelify\Api\Modules\Auth\Services\AuthService::class);

        $container->add(id: \Fidelify\Api\Modules\User\Repositories\UserRepository::class)
            ->addArgument(arg: \Fidelify\Api\Adapters\Database\MysqlAdapter::create());
        $container->add(id: \Fidelify\Api\Modules\User\Services\UserService::class)
            ->addArgument(arg: \Fidelify\Api\Modules\User\Repositories\UserRepository::class);
        $container->add(id: \Fidelify\Api\Modules\User\Controllers\UserController::class)
            ->addArgument(arg: \Fidelify\Api\Modules\Util\Services\ValidationService::class)
            ->addArgument(arg: \Fidelify\Api\Modules\User\Services\UserService::class);

        return $container;
    }
}
