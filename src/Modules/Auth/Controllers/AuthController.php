<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Controllers;

use Fidelify\Api\Modules\Auth\Entities\SignupRequestEntity;
use Fidelify\Api\Modules\Auth\Entities\SignupResponseEntity;
use Fidelify\Api\Modules\Auth\Services\AuthService;

use Fidelify\Api\Modules\Util\Services\ValidationService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthController
{
    public static function signup(RequestInterface $request): ResponseInterface
    {
        try {
            $json = json_decode(json: $request->getBody()->getContents(), associative: true);

            $validationService = ValidationService::create();
            $validationService->validate(data: $json, rules: [
                'profileId' => 'required|integer',
                'name' => 'required|regex:/^[A-Za-z\s]+$/',
                'email' => 'required|email',
                'password' => 'required|not_regex:/[\(\)\[\]\{\}\<\>]/',
            ]);

            $signupRequestEntity = new SignupRequestEntity(
                profileId: $json['profileId'],
                name: $json['name'],
                email: $json['email'],
                password: $json['password'],
            );

            $authService = AuthService::create();
            $token = $authService->signup(signupRequestEntity: $signupRequestEntity);

            $signupResponseEntity = new SignupResponseEntity(
                token: $token,
            );

            return new JsonResponse(data: $signupResponseEntity, status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to signup',
                'reason' => $th->getMessage(),
            ], status: 400);
        }
    }

    public static function signin(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(data: [
                'request' => $request->getBody()->getContents(),
            ], status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to signin',
                'reason' => $th->getMessage(),
            ], status: 400);
        }
    }

    public static function signout(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(data: [
                'request' => $request->getBody()->getContents(),
            ], status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to signout',
                'reason' => $th->getMessage(),
            ], status: 400);
        }
    }

    public static function reset(RequestInterface $request): ResponseInterface
    {
        try {
            return new JsonResponse(data: [
                'request' => $request->getBody()->getContents(),
            ], status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to reset credentials',
                'reason' => $th->getMessage(),
            ], status: 400);
        }
    }
}
