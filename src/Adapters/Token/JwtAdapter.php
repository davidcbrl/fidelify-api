<?php

declare(strict_types=1);

namespace Fidelify\Api\Adapters\Token;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAdapter
{
    public function __construct(
        private string $secret,
    ) {}

    public static function create(): self
    {
        $jwtSecret = is_string(value: getenv(name: 'JWT_SECRET')) ? getenv(name: 'JWT_SECRET') : 'fidelify';

        return new static($jwtSecret);
    }

    public function encode(array $data): string
    {
        return JWT::encode($data, $this->secret, 'HS256');
    }

    public function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}
