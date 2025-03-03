<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Fidelify\Api\Modules\Auth\Entities\SigninRequestEntity;
use Fidelify\Api\Modules\Auth\Entities\SignupRequestEntity;
use Fidelify\Api\Modules\Auth\Entities\AuthenticatedResponseEntity;
use Fidelify\Api\Modules\Auth\Services\AuthService;
use Fidelify\Api\Modules\Util\Services\ValidationService;

class AuthController
{
    public function __construct(
        private ValidationService $validationService,
        private AuthService $authService,
    ) {}

    public function signup(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $json = json_decode(json: $request->getBody()->getContents(), associative: true);

            $this->validationService->validate(data: $json, rules: [
                'profile' => 'required|uuid',
                'name' => 'required|regex:/^[A-Za-z\s]+$/',
                'email' => 'required|email',
                'password' => 'required|not_regex:/[\(\)\[\]\{\}\<\>]/',
            ]);

            $signupRequestEntity = new SignupRequestEntity(
                profile: $json['profile'],
                name: $json['name'],
                email: $json['email'],
                password: $json['password'],
            );

            $this->authService->signup(signupRequestEntity: $signupRequestEntity);

            $signinRequestEntity = new SigninRequestEntity(
                email: $json['email'],
                password: $json['password'],
            );

            $token = $this->authService->signin(signinRequestEntity: $signinRequestEntity);

            $authenticatedResponseEntity = new AuthenticatedResponseEntity(
                token: $token,
            );

            return new JsonResponse(data: $authenticatedResponseEntity, status: 201);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to signup',
                'reason' => $th->getMessage(),
            ], status: (int) $th->getCode() === 0 ? 400 : (int) $th->getCode());
        }
    }

    public function signin(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $json = json_decode(json: $request->getBody()->getContents(), associative: true);

            $this->validationService->validate(data: $json, rules: [
                'email' => 'required|email',
                'password' => 'required|not_regex:/[\(\)\[\]\{\}\<\>]/',
            ]);

            $signinRequestEntity = new SigninRequestEntity(
                email: $json['email'],
                password: $json['password'],
            );

            $token = $this->authService->signin(signinRequestEntity: $signinRequestEntity);

            $authenticatedResponseEntity = new AuthenticatedResponseEntity(
                token: $token,
            );

            return new JsonResponse(data: $authenticatedResponseEntity, status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to signin',
                'reason' => $th->getMessage(),
            ], status: (int) $th->getCode() === 0 ? 400 : (int) $th->getCode());
        }
    }
}
