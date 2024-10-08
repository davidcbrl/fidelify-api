<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Services;

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

        return new static($repository);
    }

    public function signup(SignupRequestEntity $signupRequestEntity): string
    {
        $signupRequestEntity->password = base64_encode(string: password_hash(
            password: $signupRequestEntity->password,
            algo: PASSWORD_BCRYPT,
        ));

        return $this->repository->signup(signupRequestEntity: $signupRequestEntity);
    }
}
