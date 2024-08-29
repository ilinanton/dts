<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitlabCommit extends AbstractMigration
{
    private const TABLE_NAME = 'gitlab_commit';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['id', 'project_id'],
            ]
        )
            ->addColumn('id', 'string', ['length' => 128, 'null' => false])
            ->addColumn('project_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('title', 'string', ['length' => 1024, 'null' => true])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('web_url', 'string', ['length' => 255, 'null' => false])

            ->addColumn('author_name', 'string', ['length' => 255, 'null' => true])
            ->addColumn('author_email', 'string', ['length' => 255, 'null' => true])
            ->addColumn('authored_date', 'datetime', ['null' => false])

            ->addColumn('committer_name', 'string', ['length' => 255, 'null' => true])
            ->addColumn('committer_email', 'string', ['length' => 255, 'null' => true])
            ->addColumn('committed_date', 'datetime', ['null' => false])

            ->addIndex(['created_at'])

            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
