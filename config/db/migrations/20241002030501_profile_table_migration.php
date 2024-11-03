<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class ProfileTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('profile')) {
            return;
        }

        $this->table('profile')
            ->addColumn('code', 'uuid', ['limit' => '255', 'default' => Literal::from('(UUID())')])
            ->addColumn('name', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['code'])
            ->save();

        $this->table('profile')
            ->insert([
                ['name' => 'company'],
                ['name' => 'employee'],
                ['name' => 'customer'],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('profile')) {
            return;
        }

        $this->table('profile')->drop()->save();
    }
}
