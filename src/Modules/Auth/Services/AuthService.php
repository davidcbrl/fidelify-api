<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Services;

use Fidelify\Api\Modules\Auth\Entities\SigninRequestEntity;
use Fidelify\Api\Modules\Auth\Entities\SignupRequestEntity;
use Fidelify\Api\Modules\Auth\Repositories\AuthRepository;

class AuthService
{
    public function __construct(
        private AuthRepository $repository,
    ) {}

    public static function create(): self
    {
        $repository = AuthRepository::create();

        return new self(repository: $repository);
    }

    public function signup(SignupRequestEntity $signupRequestEntity): void
    {
        $this->repository->signup(signupRequestEntity: $signupRequestEntity);
    }

    public function signin(SigninRequestEntity $signinRequestEntity): string
    {
        return $this->repository->signin(signinRequestEntity: $signinRequestEntity);
    }
}
