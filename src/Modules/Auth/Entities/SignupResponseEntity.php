<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Entities;

class SignupResponseEntity
{
    public function __construct(
        public string $token,
    ) {}
}
