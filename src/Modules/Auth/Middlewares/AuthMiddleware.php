<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Middlewares;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Fidelify\Api\Adapters\Token\JwtAdapter;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private JwtAdapter $jwtAdapter,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $authorization = $request->getHeader('Authorization');

            if (!isset($authorization) || empty($authorization)) {
                throw new \Exception(message: 'Authorization not found', code: 401);
            }

            $data = $this->jwtAdapter->decode(token: $authorization[0]);

            if (isset($data['code']) && !empty($data['code'])) {
                $request = $request->withAttribute('code', $data['code']);
            }

            return $handler->handle(request: $request);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Unauthorized',
                'reason' => $th->getMessage(),
            ], status: (int) $th->getCode() === 0 ? 400 : (int) $th->getCode());
        }
    }
}
