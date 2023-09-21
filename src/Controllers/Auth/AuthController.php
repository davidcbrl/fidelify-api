<?php

declare(strict_types=1);

namespace Fidelify\Api\Controllers\Auth;

use Fidelify\Api\Endpoint;
use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response;

#[Endpoint(group: '/auth')]
class AuthController
{
    #[Endpoint(method: 'POST', path: '/signup')]
    public static function signup(Request $request): Response
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail to signup',
            ]), 400);
        }
    }

    #[Endpoint(method: 'POST', path: '/login')]
    public static function login(Request $request): Response
    {
        try {
            return new Response(json_encode([]), 200);
        } catch (\Throwable $th) {
            return new Response(json_encode([
                'error' => 'Fail to login',
            ]), 400);
        }
    }

    #[Endpoint(method: 'POST', path: '/password')]
    public static function password(Request $request): Response
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
