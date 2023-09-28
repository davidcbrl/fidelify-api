<?php

declare(strict_types=1);

namespace Fidelify\Api;

use Fidelify\Api\Route;
use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Base
{
    public static function readBody(RequestInterface $request): ?array
    {
        $body = $request->getBody()->getContents();

        if (empty($body)) {
            return null;
        }

        return json_decode($body, true);
    }
}
