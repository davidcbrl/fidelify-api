<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserInfoTableMigration extends AbstractMigration
{
    public function up(): void
    {
        if ($this->hasTable('user_info')) {
            return;
        }

        $this->table('user_info')
            ->addColumn('user_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('document', 'string', ['limit' => '255'])
            ->addColumn('image', 'string', ['limit' => '255'])
            ->addColumn('active', 'boolean', ['default' => 1])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey(['user_id'], 'user', 'id', ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION'])
            ->create();

        $this->table('user_info')
            ->insert([
                ['user_id' => 1, 'document' => '11111111111', 'image' => 'https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/78e88f76-29bf-4289-9624-719aec0f7bcb/e516f677-4846-4a28-9707-ba00ffa49479.png'],
                ['user_id' => 2, 'document' => '22222222222', 'image' => 'https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/78e88f76-29bf-4289-9624-719aec0f7bcb/e516f677-4846-4a28-9707-ba00ffa49479.png'],
                ['user_id' => 3, 'document' => '33333333333', 'image' => 'https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/78e88f76-29bf-4289-9624-719aec0f7bcb/e516f677-4846-4a28-9707-ba00ffa49479.png'],
            ])
            ->save();
    }

    public function down(): void
    {
        if (!$this->hasTable('user_info')) {
            return;
        }

        $this->table('user_info')->drop()->save();
    }
}
