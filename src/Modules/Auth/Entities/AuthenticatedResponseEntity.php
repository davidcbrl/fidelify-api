<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Entities;

class AuthenticatedResponseEntity
{
    public function __construct(
        public string $token,
    ) {}
}
