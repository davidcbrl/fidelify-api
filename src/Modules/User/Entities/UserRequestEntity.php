<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Entities;

class UserRequestEntity
{
    public function __construct(
        public string $name,
        public string $email,
        public ?int $document = null,
    ) {}
}
