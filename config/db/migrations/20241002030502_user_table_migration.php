<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class UserTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('user')) {
            return;
        }

        $this->table('user')
            ->addColumn('profile_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('code', 'uuid', ['limit' => '255', 'default' => Literal::from('(UUID())')])
            ->addColumn('email', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('password', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('name', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey(['profile_id'], 'profile', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addIndex(['code'])
            ->addIndex(['email', 'password'])
            ->create();

        $this->table('user')
            ->insert([
                ['profile_id' => 1, 'email' => 'company@master.com', 'password' => 'companymaster', 'name' => 'master company'],
                ['profile_id' => 2, 'email' => 'employee@master.com', 'password' => 'employeemaster', 'name' => 'master employee'],
                ['profile_id' => 3, 'email' => 'customer@master.com', 'password' => 'customermaster', 'name' => 'master customer'],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('user')) {
            return;
        }

        $this->table('user')->drop()->save();
    }
}
