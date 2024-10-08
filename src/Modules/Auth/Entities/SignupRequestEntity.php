<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Entities;

class SignupRequestEntity
{
    public function __construct(
        public int $profileId,
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
