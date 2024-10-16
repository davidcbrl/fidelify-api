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

        return new self(secret: $jwtSecret);
    }

    public function encode(array $data): string
    {
        $issuer = 'fidelify.com.br';
        $interval = new \DateInterval(duration: 'P1D');
        $expiration = (new \DateTime(datetime: 'now'))->add(interval: $interval)->getTimestamp();

        $payload = [
            'iss' => $issuer,
            'exp' => $expiration,
            'data' => json_encode(value: $data),
        ];

        return JWT::encode(payload: $payload, key: $this->secret, alg: 'HS256');
    }

    public function decode(string $token): array
    {
        $key = new Key(keyMaterial: $this->secret, algorithm: 'HS256');

        $payload = (array) JWT::decode(jwt: $token, keyOrKeyArray: $key);

        return json_decode(json: $payload['data']);
    }
}
