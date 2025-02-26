<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Entities;

class UserEntity
{
    public function __construct(
        public string $profile,
        public string $name,
        public string $email,
        public string $password,
        public ?int $document = null,
        public ?string $image = null,
    ) {}
}
