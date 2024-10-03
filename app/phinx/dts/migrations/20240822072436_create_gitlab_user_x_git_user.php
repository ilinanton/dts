<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitlabUserXGitUser extends AbstractMigration
{
    private const TABLE_NAME = 'gitlab_user_x_git_user';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['gitlab_user_id', 'git_email'],
            ]
        )
            ->addColumn('gitlab_user_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('git_email', 'string', ['length' => 255, 'null' => false])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
