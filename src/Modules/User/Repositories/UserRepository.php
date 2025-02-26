<?php

declare(strict_types=1);

namespace Fidelify\Api\Modules\User\Repositories;

use Fidelify\Api\Adapters\Database\MysqlAdapter;
use Fidelify\Api\Modules\User\Entities\UserEntity;

class UserRepository
{
    public function __construct(
        private MysqlAdapter $databaseAdapter,
    ) {}

    public static function create(): self
    {
        $databaseAdapter = MysqlAdapter::create();

        return new self(databaseAdapter: $databaseAdapter);
    }

    public function save(UserEntity $userEntity): void
    {
        $userEntity->password = base64_encode(string: password_hash(
            password: $userEntity->password,
            algo: PASSWORD_BCRYPT,
        ));

        $selectResult = $this->databaseAdapter->select(
            query: 'SELECT id as profileId FROM profile WHERE code = :code LIMIT 1',
            params: [':code' => $userEntity->profile],
        );

        $insertResult = $this->databaseAdapter->execute(
            query: 'INSERT INTO user (profile_id, name, email, password) VALUES (:profileId, :name, :email, :password)',
            params: [
                ':profileId' => $selectResult['profileId'],
                ':name' => $userEntity->name,
                ':email' => $userEntity->email,
                ':password' => $userEntity->password,
            ],
        );

        if (!isset($insertResult) || empty($insertResult)) {
            throw new \Exception(message: 'Record not saved', code: 400);
        }
    }

    public function get(string $code): UserEntity
    {
        $selectResult = $this->databaseAdapter->select(
            query: '
                SELECT
                    u.name,
                    u.email,
                    p.code as profile,
                    uf.document,
                    uf.image
                FROM user u
                JOIN profile p ON p.id = u.profile_id
                LEFT JOIN user_info uf ON uf.user_id = u.id
                WHERE u.code = :code
                LIMIT 1
            ',
            params: [':code' => $code],
        );

        $user = new UserEntity(
            profile: $selectResult['profile'],
            name: $selectResult['name'],
            email: $selectResult['email'],
            document: $selectResult['document'],
            image: $selectResult['image'],
            password: '',
        );

        unset($user->password);

        return $user;
    }
}
