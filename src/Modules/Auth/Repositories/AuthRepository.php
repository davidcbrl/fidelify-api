<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Repositories;

use Fidelify\Api\Adapters\Database\MysqlAdapter;
use Fidelify\Api\Adapters\Token\JwtAdapter;
use Fidelify\Api\Modules\Auth\Entities\SigninRequestEntity;
use Fidelify\Api\Modules\User\Repositories\UserRepository;

class AuthRepository extends UserRepository
{
    public function __construct(
        private MysqlAdapter $databaseAdapter,
        private JwtAdapter $tokenAdapter,
    ) {
        parent::__construct(databaseAdapter: $databaseAdapter);
    }

    public function signin(SigninRequestEntity $signinRequestEntity): string
    {
        $selectResult = $this->databaseAdapter->select(
            query: 'SELECT code, password FROM user WHERE email = :email AND active = 1 LIMIT 1',
            params: ['email' => $signinRequestEntity->email],
        );

        if (!isset($selectResult) || empty($selectResult)) {
            throw new \Exception(message: 'Incorrect user or password', code: 400);
        }

        $passwordResult = password_verify(
            password: $signinRequestEntity->password,
            hash: base64_decode(string: $selectResult['password']),
        );

        if (!$passwordResult) {
            throw new \Exception(message: 'Incorrect user or password', code: 400);
        }

        unset($selectResult['password']);

        $tokenResult = $this->tokenAdapter->encode(data: $selectResult);

        if (!isset($tokenResult) || empty($tokenResult)) {
            throw new \Exception(message: 'Token not generated', code: 400);
        }

        return $tokenResult;
    }
}
