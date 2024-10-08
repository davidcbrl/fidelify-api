<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Repositories;

use Fidelify\Api\Adapters\Database\MysqlAdapter;
use Fidelify\Api\Adapters\Token\JwtAdapter;
use Fidelify\Api\Modules\Auth\Entities\SignupRequestEntity;

class AuthRepository
{
    public function __construct(
        private MysqlAdapter $databaseAdapter,
        private JwtAdapter $tokenAdapter,
    ) {}

    public static function create(): self
    {
        $databaseAdapter = MysqlAdapter::create();
        $tokenAdapter = JwtAdapter::create();

        return new static($databaseAdapter, $tokenAdapter);
    }

    public function signup(SignupRequestEntity $signupRequestEntity): string
    {
        $insertResult = $this->databaseAdapter->execute(
            query: 'INSERT INTO user (profile_id, name, email, password) VALUES (:profileId, :name, :email, :password)',
            params: [
                ':profileId' => $signupRequestEntity->profileId,
                ':name' => $signupRequestEntity->name,
                ':email' => $signupRequestEntity->email,
                ':password' => $signupRequestEntity->password,
            ],
        );

        if (!isset($insertResult) || empty($insertResult)) {
            throw new \Exception(message: 'Fail to persist to database', code: 400);
        }

        $selectResult = $this->databaseAdapter->select(
            query: 'SELECT code, email FROM user WHERE id = :id',
            params: ['id' => $insertResult],
        );

        if (!isset($selectResult) || empty($selectResult)) {
            throw new \Exception(message: 'Fail to get from database', code: 400);
        }

        $tokenResult = $this->tokenAdapter->encode(data: $selectResult);

        if (!isset($tokenResult) || empty($tokenResult)) {
            throw new \Exception(message: 'Fail to generate token', code: 400);
        }

        return $tokenResult;
    }
}
