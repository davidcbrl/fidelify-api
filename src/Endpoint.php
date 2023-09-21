<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Attribute;

#[Attribute]
class Endpoint
{
    public function __construct(
        public ?string $group,
        public ?string $method,
        public ?string $path,
        public ?string $body
    ) {
        $this->group = $group;
        $this->method = $method;
        $this->path = $path;
        $this->body = $body;
    }
}
