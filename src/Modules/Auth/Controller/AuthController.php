<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Controller;

use Fidelify\Api\Base;
use Fidelify\Api\Modules\Auth\Model\AuthModel;
use Fidelify\Api\Modules\Auth\Model\AuthRequest;
use Fidelify\Api\Modules\Auth\Model\AuthResponse;
use Fidelify\Api\Modules\Auth\Service\AuthService;
use Fidelify\Api\Route;
use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[Route(group: '/auth')]
class AuthController extends Base
{
    public function __construct(
        public AuthService $authService,
    ) {}

    #[Route(method: 'POST', path: '/signup')]
    public function signup(RequestInterface $request): ResponseInterface
    {
        try {
            $json = self::readBody($request);

            $authRequest = new AuthRequest(
                new AuthModel(
                    $json['email'],
                    $json['password'],
                ),
            );

            $authResponse = new AuthResponse(
                $this->authService->signup($authRequest),
            );

            return new Response(json_encode([
                'token' => $authResponse->token,
            ]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail to signup',
            ]), 400);
        }
    }

    #[Route(method: 'POST', path: '/login')]
    public function login(RequestInterface $request): ResponseInterface
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail to login',
            ]), 400);
        }
    }

    #[Route(method: 'POST', path: '/password')]
    public function password(RequestInterface $request): ResponseInterface
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail reset password',
            ]), 400);
        }
    }
}
