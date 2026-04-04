<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameGitlabCommitStatsToGitlabCommit extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('RENAME TABLE gitlab_commit_stats TO gitlab_commit');
    }

    public function down(): void
    {
        $this->execute('RENAME TABLE gitlab_commit TO gitlab_commit_stats');
    }
}
