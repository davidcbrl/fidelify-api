<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class AddressTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('address')) {
            return;
        }

        $this->table('address')
            ->addColumn('code', 'uuid', ['limit' => '255', 'default' => Literal::from('(UUID())')])
            ->addColumn('street', 'string', ['limit' => '255'])
            ->addColumn('number', 'string', ['limit' => '255'])
            ->addColumn('city', 'string', ['limit' => '255'])
            ->addColumn('state', 'string', ['limit' => '255'])
            ->addColumn('postcode', 'string', ['limit' => '255'])
            ->addColumn('complement', 'string', ['limit' => '255'])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['code'])
            ->create();

        $this->table('address')
            ->insert([
                ['street' => 'master company street', 'number' => '1', 'city' => 'master company city', 'state' => 'master company state', 'postcode' => '11111111', 'complement' => 'master company complement'],
                ['street' => 'master employee street', 'number' => '2', 'city' => 'master employee city', 'state' => 'master employee state', 'postcode' => '22222222', 'complement' => 'master employee complement'],
                ['street' => 'master customer street', 'number' => '3', 'city' => 'master customer city', 'state' => 'master customer state', 'postcode' => '33333333', 'complement' => 'master customer complement'],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('address')) {
            return;
        }

        $this->table('address')->drop()->save();
    }
}
