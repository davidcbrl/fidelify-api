<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class ContactTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('contact')) {
            return;
        }

        $this->table('contact')
            ->addColumn('code', 'uuid', ['limit' => '255', 'default' => Literal::from('(UUID())')])
            ->addColumn('prefix', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('number', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['code'])
            ->create();

        $this->table('contact')
            ->insert([
                ['prefix' => '1', 'number' => '11111111111'],
                ['prefix' => '2', 'number' => '22222222222'],
                ['prefix' => '3', 'number' => '33333333333'],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('contact')) {
            return;
        }

        $this->table('contact')->drop()->save();
    }
}
