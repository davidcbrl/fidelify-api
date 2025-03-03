<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Entities;

class UserInfoRequestEntity
{
    public function __construct(
        public ?string $name = null,
        public ?string $document = null,
        public ?string $image = null,
    ) {}
}
