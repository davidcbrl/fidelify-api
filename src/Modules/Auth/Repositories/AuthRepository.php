<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\Auth\Repositories;

use Fidelify\Api\Adapters\Database\MysqlAdapter;
use Fidelify\Api\Adapters\Token\JwtAdapter;
use Fidelify\Api\Modules\Auth\Entities\SigninRequestEntity;
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

        return new self(databaseAdapter: $databaseAdapter, tokenAdapter: $tokenAdapter);
    }

    public function signup(SignupRequestEntity $signupRequestEntity): void
    {
        $signupRequestEntity->password = base64_encode(string: password_hash(
            password: $signupRequestEntity->password,
            algo: PASSWORD_BCRYPT,
        ));

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
            throw new \Exception(message: 'Record not saved', code: 400);
        }
    }

    public function signin(SigninRequestEntity $signinRequestEntity): string
    {
        $selectResult = $this->databaseAdapter->select(
            query: 'SELECT code, email, password FROM user WHERE email = :email LIMIT 1',
            params: ['email' => $signinRequestEntity->email],
        );

        if (!isset($selectResult) || empty($selectResult)) {
            throw new \Exception(message: 'Record not found', code: 400);
        }

        $passwordResult = password_verify(
            password: $signinRequestEntity->password,
            hash: base64_decode(string: $selectResult['password']),
        );

        if (!$passwordResult) {
            throw new \Exception(message: 'Incorrect password', code: 400);
        }

        $tokenResult = $this->tokenAdapter->encode(data: $selectResult);

        if (!isset($tokenResult) || empty($tokenResult)) {
            throw new \Exception(message: 'Token not generated', code: 400);
        }

        return $tokenResult;
    }
}
