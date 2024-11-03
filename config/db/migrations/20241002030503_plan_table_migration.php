<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class PlanTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('plan')) {
            return;
        }

        $this->table('plan')
            ->addColumn('code', 'uuid', ['limit' => '255', 'default' => Literal::from('(UUID())')])
            ->addColumn('name', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('value', 'integer', ['signed' => true])
            ->addColumn('description', 'string', ['limit' => '255'])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['code'])
            ->create();

        $this->table('plan')
            ->insert([
                ['name' => 'free', 'value' => 0.0, 'description' => 'free plan'],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('plan')) {
            return;
        }

        $this->table('plan')->drop()->save();
    }
}
