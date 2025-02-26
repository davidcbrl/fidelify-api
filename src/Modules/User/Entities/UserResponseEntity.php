<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Entities;

class UserResponseEntity
{
    public function __construct(
        public UserEntity $user,
    ) {}
}
