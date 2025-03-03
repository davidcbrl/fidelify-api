<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Services;

use Fidelify\Api\Modules\Auth\Entities\SigninRequestEntity;
use Fidelify\Api\Modules\Auth\Entities\SignupRequestEntity;
use Fidelify\Api\Modules\Auth\Repositories\AuthRepository;
use Fidelify\Api\Modules\User\Entities\UserEntity;

class AuthService
{
    public function __construct(
        private AuthRepository $repository,
    ) {}

    public function signup(SignupRequestEntity $signupRequestEntity): void
    {
        $userEntity = new UserEntity(
            profile: $signupRequestEntity->profile,
            name: $signupRequestEntity->name,
            email: $signupRequestEntity->email,
            password: $signupRequestEntity->password,
        );

        $this->repository->save(userEntity: $userEntity);
    }

    public function signin(SigninRequestEntity $signinRequestEntity): string
    {
        return $this->repository->signin(signinRequestEntity: $signinRequestEntity);
    }
}
