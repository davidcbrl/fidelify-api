<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserAccessTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('user_access')) {
            return;
        }

        $this->table('user_access')
            ->addColumn('user_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('company_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey(['user_id'], 'user', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey(['company_id'], 'company', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();

        $this->table('user_access')
            ->insert([
                ['user_id' => 1, 'company_id' => 1],
                ['user_id' => 2, 'company_id' => 1],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('user_access')) {
            return;
        }

        $this->table('user_access')->drop()->save();
    }
}
