<?php

declare(strict_types=1);

namespace Fidelify\Api\Adapters\Database;

class MysqlAdapter
{
    public function __construct(
        private \PDO $pdo,
        private ?\PDOStatement $statement = null,
    ) {}

    public static function create(): self
    {
        $dbType = is_string(value: getenv(name: 'DB_TYPE')) ? getenv(name: 'DB_TYPE') : 'mysql';
        $dbHost = is_string(value: getenv(name: 'DB_HOST')) ? getenv(name: 'DB_HOST') : 'fidelify-db';
        $dbPort = is_numeric(value: getenv(name: 'DB_PORT')) ? getenv(name: 'DB_PORT') : 3306;
        $dbName = is_string(value: getenv(name: 'DB_NAME')) ? getenv(name: 'DB_NAME') : 'fidelify';
        $dbUser = is_string(value: getenv(name: 'DB_USER')) ? getenv(name: 'DB_USER') : 'root';
        $dbPass = is_string(value: getenv(name: 'DB_PASS')) ? getenv(name: 'DB_PASS') : '1234';

        $dbDsn = "$dbType:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8";
        $pdo = new \PDO(dsn: $dbDsn, username: $dbUser, password: $dbPass);

        return new self(pdo: $pdo);
    }

    public function execute(string $query, array $params): bool
    {
        $this->statement = $this->pdo->prepare(query: $query);

        foreach ($params as $key => $value) {
            if (is_null(value: $value)) {
                $this->statement->bindValue(param: $key, value: $value, type: \PDO::PARAM_NULL);
                continue;
            }

            $var = is_numeric(value: $value) ? (int) $value : (string) $value;
            $type = is_numeric(value: $value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;

            $this->statement->bindValue(param: $key, value: $var, type: $type);
        }

        return $this->statement->execute();
    }

    public function select(string $query, array $params): array
    {
        $this->execute(query: $query, params: $params);

        if (empty($this->statement)) {
            return [];
        }

        if ($this->statement->rowCount() === 1) {
            return $this->statement->fetch(mode: \PDO::FETCH_ASSOC);
        }

        return $this->statement->fetchAll(mode: \PDO::FETCH_ASSOC);
    }
}
