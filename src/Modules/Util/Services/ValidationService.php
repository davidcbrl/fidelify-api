<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Util\Services;

use Fidelify\Api\Adapters\Validation\IlluminateAdapter;

class ValidationService
{
    public function __construct(
        private IlluminateAdapter $adapter,
    ) {}

    public static function create(): self
    {
        $adapter = IlluminateAdapter::create();

        return new self(adapter: $adapter);
    }

    public function validate(array $data, array $rules): void
    {
        $this->adapter->validate(data: $data, rules: $rules);
    }
}
