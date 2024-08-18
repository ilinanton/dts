<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitLabCommit extends AbstractMigration
{
    private const TABLE_NAME = 'git_lab_commit';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['id'],
            ]
        )
            ->addColumn('id', 'string', ['length' => 128, 'null' => false])
            ->addColumn('short_id', 'string', ['length' => 32, 'null' => false])
            ->addColumn('title', 'string', ['length' => 1024, 'null' => true])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('web_url', 'string', ['length' => 255, 'null' => false])

            ->addColumn('author_name', 'string', ['length' => 255, 'null' => true])
            ->addColumn('author_email', 'string', ['length' => 255, 'null' => true])
            ->addColumn('authored_date', 'datetime', ['null' => false])

            ->addColumn('committer_name', 'string', ['length' => 255, 'null' => true])
            ->addColumn('committer_email', 'string', ['length' => 255, 'null' => true])
            ->addColumn('committed_date', 'datetime', ['null' => false])


            ->addColumn('stats_additions', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('stats_deletions', 'integer', ['signed' => false, 'null' => true])
            ->addColumn('stats_total', 'integer', ['signed' => false, 'null' => true])

            ->addIndex(['short_id'])
            ->addIndex(['created_at'])
            ->addIndex(['stats_additions'])
            ->addIndex(['stats_deletions'])
            ->addIndex(['stats_total'])

            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
