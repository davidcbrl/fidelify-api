<?php

declare(strict_types=1);

namespace Fidelify\Api\Controllers\User;

use Fidelify\Api\Endpoint;
use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response;

#[Endpoint(group: '/user')]
class UserController
{
    #[Endpoint(method: 'GET', path: '/list')]
    public static function getAll(Request $request): Response
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail to get all users',
            ]), 400);
        }
    }

    #[Endpoint(method: 'GET', path: '/{id}')]
    public static function getOne(Request $request): Response
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail to get user',
            ]), 400);
        }
    }

    #[Endpoint(method: 'POST', path: '/{id}')]
    public static function create(Request $request): Response
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail create user',
            ]), 400);
        }
    }

    #[Endpoint(method: 'PUT', path: '/{id}')]
    public static function update(Request $request): Response
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail update user',
            ]), 400);
        }
    }
}
