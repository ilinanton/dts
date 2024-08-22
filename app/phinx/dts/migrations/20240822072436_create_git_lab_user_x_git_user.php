<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitLabUserXGitUser extends AbstractMigration
{
    private const TABLE_NAME = 'git_lab_user_x_git_user';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['git_lab_user_id', 'committer_email'],
            ]
        )
            ->addColumn('git_lab_user_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('committer_email', 'string', ['length' => 255, 'null' => false])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
