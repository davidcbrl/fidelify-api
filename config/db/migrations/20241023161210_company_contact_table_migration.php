<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CompanyContactTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('company_contact')) {
            return;
        }

        $this->table('company_contact')
            ->addColumn('company_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('contact_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey(['company_id'], 'company', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->addForeignKey(['contact_id'], 'contact', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();

        $this->table('company_contact')
            ->insert([
                ['company_id' => 1, 'contact_id' => 1],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('company_contact')) {
            return;
        }

        $this->table('company_contact')->drop()->save();
    }
}
