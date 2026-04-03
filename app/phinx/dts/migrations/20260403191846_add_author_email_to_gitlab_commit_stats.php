<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAuthorEmailToGitlabCommitStats extends AbstractMigration
{
    public function change(): void
    {
        $this->table('gitlab_commit_stats')
            ->addColumn('author_email', 'string', [
                'limit' => 255,
                'null' => false,
                'after' => 'project_id',
            ])
            ->update();
    }
}
