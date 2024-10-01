<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthController
{
    public static function signup(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail to signup',
            ]), 400);
        }
    }

    public static function signin(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail to signin',
            ]), 400);
        }
    }

    public static function signout(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail to signout',
            ]), 400);
        }
    }

    public static function reset(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail to reset credentials',
            ]), 400);
        }
    }
}
