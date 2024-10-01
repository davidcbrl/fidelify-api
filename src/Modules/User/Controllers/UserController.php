<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    public static function list(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail to list users',
            ]), 400);
        }
    }

    public static function get(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail to get user',
            ]), 400);
        }
    }

    public static function create(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail create user',
            ]), 400);
        }
    }

    public static function update(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(json_encode([
                'request' => $request->getBody()->getContents(),
            ]), 200);
        } catch (\Throwable $th) {
            return new JsonResponse(json_encode([
                'error' => 'Fail update user',
            ]), 400);
        }
    }
}
