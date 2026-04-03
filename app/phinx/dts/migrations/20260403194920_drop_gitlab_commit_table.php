<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DropGitlabCommitTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('gitlab_commit')->drop()->save();
    }

    public function down(): void
    {
        $this->table('gitlab_commit', ['id' => false, 'primary_key' => ['id', 'project_id']])
            ->addColumn('id', 'string', ['limit' => 128, 'null' => false])
            ->addColumn('project_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('git_commit_id', 'string', ['limit' => 128, 'null' => false])
            ->addColumn('title', 'string', ['limit' => 1024, 'null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('web_url', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('author_name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('author_email', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('authored_date', 'datetime', ['null' => false])
            ->addColumn('committer_name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('committer_email', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('committed_date', 'datetime', ['null' => false])
            ->addIndex(['created_at'])
            ->create();
    }
}
