<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitlabLabel extends AbstractMigration
{
    private const TABLE_NAME = 'gitlab_label';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['id'],
            ]
        )
            ->addColumn('id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('name', 'string', ['length' => 255, 'null' => false])
            ->addColumn('color', 'string', ['length' => 32, 'null' => false])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
