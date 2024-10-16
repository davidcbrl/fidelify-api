<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Entities;

class SigninRequestEntity
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
