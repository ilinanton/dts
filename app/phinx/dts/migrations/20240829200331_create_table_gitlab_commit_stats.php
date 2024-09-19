<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableGitlabCommitStats extends AbstractMigration
{
    private const TABLE_NAME = 'gitlab_commit_stats';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['git_commit_id', 'project_id'],
            ]
        )
            ->addColumn('git_commit_id', 'string', ['length' => 128, 'null' => false])
            ->addColumn('project_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('files', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('additions', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('deletions', 'integer', ['signed' => false, 'null' => true])

            ->addIndex(['files'])
            ->addIndex(['additions'])
            ->addIndex(['deletions'])

            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
