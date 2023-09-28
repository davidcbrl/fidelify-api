<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Model;

class AuthModel
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}

class AuthRequest
{
    public function __construct(
        public AuthModel $auth,
    ) {}
}

class AuthResponse
{
    public function __construct(
        public string $token,
    ) {}
}
