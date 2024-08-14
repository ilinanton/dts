<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitLabProject extends AbstractMigration
{
    private const TABLE_NAME = 'git_lab_project';

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
            ->addColumn('default_branch', 'string', ['length' => 255, 'null' => false])
            ->addColumn('web_url', 'string', ['length' => 500, 'null' => false])
            ->addIndex(['name'])
            ->addIndex(['default_branch'])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
