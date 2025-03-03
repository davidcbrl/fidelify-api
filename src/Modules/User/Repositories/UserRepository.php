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

    public function save(UserEntity $userEntity): void
    {
        $userEntity->password = base64_encode(string: password_hash(
            password: $userEntity->password,
            algo: PASSWORD_BCRYPT,
        ));

        $selectResult = $this->databaseAdapter->select(
            query: 'SELECT id as profileId FROM profile WHERE code = :code AND active = 1 LIMIT 1',
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
                JOIN profile p ON p.id = u.profile_id AND p.active = 1
                LEFT JOIN user_info uf ON uf.user_id = u.id AND uf.active = 1
                WHERE u.code = :code
                  AND u.active = 1
                LIMIT 1
            ',
            params: [':code' => $code],
        );

        if (!isset($selectResult) || empty($selectResult)) {
            throw new \Exception(message: 'Record not found', code: 400);
        }

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

    public function update(string $code, UserEntity $userEntity): void
    {
        $userSelectResult = $this->databaseAdapter->select(
            query: 'SELECT id FROM user WHERE code = :code AND active = 1 LIMIT 1',
            params: [':code' => $code],
        );

        if (!isset($userSelectResult) || empty($userSelectResult)) {
            throw new \Exception(message: 'Record not updated', code: 400);
        }

        $userUpdateResult = $this->databaseAdapter->execute(
            query: 'UPDATE user SET name = :name WHERE id = :id AND active = 1 LIMIT 1',
            params: [
                ':name' => $userEntity->name,
                ':id' => $userSelectResult['id'],
            ],
        );

        if (!isset($userUpdateResult) || empty($userUpdateResult)) {
            throw new \Exception(message: 'Record not updated', code: 400);
        }

        $userInfoSelectResult = $this->databaseAdapter->select(
            query: 'SELECT id FROM user_info WHERE user_id = :id AND active = 1 LIMIT 1',
            params: [':id' => $userSelectResult['id']],
        );

        if (!isset($userInfoSelectResult) || empty($userInfoSelectResult)) {
            $insertResult = $this->databaseAdapter->execute(
                query: 'INSERT INTO user_info (user_id, document, image) VALUES (:userId, :document, :image)',
                params: [
                    ':userId' => $userSelectResult['id'],
                    ':document' => $userEntity->document,
                    ':image' => $userEntity->image,
                ],
            );

            if (!isset($insertResult) || empty($insertResult)) {
                throw new \Exception(message: 'Record not updated', code: 400);
            }

            return;
        }

        $userInfoUpdateResult = $this->databaseAdapter->execute(
            query: 'UPDATE user_info SET document = :document, image = :image WHERE id = :id AND active = 1 LIMIT 1',
            params: [
                ':document' => $userEntity->document,
                ':image' => $userEntity->image,
                ':id' => $userInfoSelectResult['id'],
            ],
        );

        if (!isset($userInfoUpdateResult) || empty($userInfoUpdateResult)) {
            throw new \Exception(message: 'Record not updated', code: 400);
        }
    }
}
