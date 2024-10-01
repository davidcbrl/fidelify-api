<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Middlewares;

use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
            return (new Response())->withStatus(401);
        }

        return $handler->handle($request);
    }
}
