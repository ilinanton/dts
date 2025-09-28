<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWeeekUser extends AbstractMigration
{
    private const TABLE_NAME = 'weeek_user';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['id'],
            ]
        )
            ->addColumn('id', 'string', ['length' => 36, 'null' => false])
            ->addColumn('email', 'string', ['length' => 255, 'null' => false])
            ->addColumn('logo', 'string', ['length' => 500, 'null' => true])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
