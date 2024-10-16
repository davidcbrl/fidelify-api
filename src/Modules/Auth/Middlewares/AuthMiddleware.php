<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Middlewares;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Fidelify\Api\Adapters\Token\JwtAdapter;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $headers = getallheaders();

            if (!isset($headers['Authorization']) || empty($headers['Authorization'])) {
                throw new \Exception(message: 'Authorization not found', code: 401);
            }

            $jwtAdapter = JwtAdapter::create();
            $jwtAdapter->decode(token: $headers['Authorization']);

            return $handler->handle(request: $request);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Unauthorized',
                'reason' => $th->getMessage(),
            ], status: 401);
        }
    }
}
