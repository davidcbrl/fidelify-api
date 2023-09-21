<?php

declare(strict_types=1);

namespace Fidelify\Api\Middlewares\Auth;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $auth = $_SESSION['AUTH'] ?? false;

        if ($auth === true) {
            return $handler->handle($request);
        }

        return (new Response())->withStatus(401);
    }
}
