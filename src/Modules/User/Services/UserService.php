<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Services;

use Fidelify\Api\Modules\User\Entities\UserEntity;
use Fidelify\Api\Modules\User\Entities\UserRequestEntity;
use Fidelify\Api\Modules\User\Repositories\UserRepository;
use Fidelify\Api\Modules\Util\Enums\ProfileEnum;

class UserService
{
    public function __construct(
        private UserRepository $repository,
    ) {}

    public static function create(): self
    {
        $repository = UserRepository::create();

        return new self(repository: $repository);
    }

    public function save(UserRequestEntity $userRequestEntity): void
    {
        $userEntity = new UserEntity(
            profile: ProfileEnum::EMPLOYEE->value,
            name: $userRequestEntity->name,
            email: $userRequestEntity->email,
            password: base64_encode(string: random_bytes(length: 10)),
            document: $userRequestEntity->document,
        );

        $this->repository->save(userEntity: $userEntity);
    }

    public function get(string $code): UserEntity
    {
        return $this->repository->get(code: $code);
    }
}
