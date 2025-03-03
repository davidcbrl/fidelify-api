<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Fidelify\Api\Modules\User\Entities\UserInfoRequestEntity;
use Fidelify\Api\Modules\User\Entities\UserRequestEntity;
use Fidelify\Api\Modules\User\Entities\UserResponseEntity;
use Fidelify\Api\Modules\User\Services\UserService;
use Fidelify\Api\Modules\Util\Services\ValidationService;

class UserController
{
    public function __construct(
        private ValidationService $validationService,
        private UserService $userService,
    ) {}

    public function save(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $json = json_decode(json: $request->getBody()->getContents(), associative: true);

            $this->validationService->validate(data: $json, rules: [
                'email' => 'required|email',
                'name' => 'required|regex:/^[A-Za-z\s]+$/',
                'document' => 'required|regex:/^[0-9]+$/',
            ]);

            $userRequestEntity = new UserRequestEntity(
                email: $json['email'],
                name: $json['name'],
                document: $json['document'],
            );

            $this->userService->save(userRequestEntity: $userRequestEntity);

            return new JsonResponse(data: [], status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to save user',
                'reason' => $th->getMessage(),
            ], status: $th->getCode() === 0 ? 400 : $th->getCode());
        }
    }

    public function get(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $json = ['code' => $request->getAttribute('code', null)];

            $this->validationService->validate(data: $json, rules: [
                'code' => 'required|uuid',
            ]);

            $user = $this->userService->get(code: $json['code']);

            $userResponseEntity = new UserResponseEntity(
                user: $user,
            );

            return new JsonResponse(data: $userResponseEntity, status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to get user',
                'reason' => $th->getMessage(),
            ], status: $th->getCode() === 0 ? 400 : $th->getCode());
        }
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $json = json_decode(json: $request->getBody()->getContents(), associative: true);
            $json['code'] = $request->getAttribute('code', null);

            $this->validationService->validate(data: $json, rules: [
                'code' => 'required|uuid',
                'name' => 'nullable|regex:/^[A-Za-z\s]+$/',
                'document' => 'nullable|regex:/^[0-9]+$/',
                'image' => 'nullable|url:https',
            ]);

            $userInfoRequestEntity = new UserInfoRequestEntity(
                name: $json['name'],
                document: $json['document'],
                image: $json['image'],
            );

            $this->userService->update(code: $json['code'], userInfoRequestEntity: $userInfoRequestEntity);

            return new JsonResponse(data: [], status: 200);
        } catch (\Throwable $th) {
            return new JsonResponse(data: [
                'error' => 'Fail to update user',
                'reason' => $th->getMessage(),
            ], status: $th->getCode() === 0 ? 400 : $th->getCode());
        }
    }
}
