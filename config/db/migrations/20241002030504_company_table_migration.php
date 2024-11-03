<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class CompanyTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('company')) {
            return;
        }

        $this->table('company')
            ->addColumn('plan_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('code', 'uuid', ['limit' => '255', 'default' => Literal::from('(UUID())')])
            ->addColumn('name', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('registration', 'string', ['limit' => '255', 'null' => false])
            ->addColumn('branch', 'string', ['limit' => '255'])
            ->addColumn('image', 'string', ['limit' => '255'])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey(['plan_id'], 'plan', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addIndex(['code'])
            ->create();

        $this->table('company')
            ->insert([
                ['plan_id' => 1, 'name' => 'master company', 'registration' => '1111111111111', 'branch' => 'master branch', 'image' => 'https://cdnassets.hw.net/b8/7a/187be5784279bf05c3b6db4fe056/806861238-0714-betsky-ge-hero-tcm20-2152200.jpg'],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('company')) {
            return;
        }

        $this->table('company')->drop()->save();
    }
}
