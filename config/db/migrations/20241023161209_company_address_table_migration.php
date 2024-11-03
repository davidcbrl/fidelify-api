<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CompanyAddressTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('company_address')) {
            return;
        }

        $this->table('company_address')
            ->addColumn('company_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('address_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey(['company_id'], 'company', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey(['address_id'], 'address', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();

        $this->table('company_address')
            ->insert([
                ['company_id' => 1, 'address_id' => 1],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('company_address')) {
            return;
        }

        $this->table('company_address')->drop()->save();
    }
}
