<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAuthorDateToGitlabCommitStats extends AbstractMigration
{
    public function change(): void
    {
        $this->table('gitlab_commit_stats')
            ->addColumn('author_date', 'datetime', [
                'null' => false,
                'after' => 'author_email',
            ])
            ->update();
    }
}
