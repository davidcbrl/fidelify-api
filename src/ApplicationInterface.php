<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ApplicationInterface
{
    public static function create(): self;
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
