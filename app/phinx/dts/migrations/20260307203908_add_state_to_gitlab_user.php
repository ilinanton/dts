<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddStateToGitlabUser extends AbstractMigration
{
    public function change(): void
    {
        $this->table('gitlab_user')
            ->addColumn('state', 'string', [
                'limit' => 32,
                'null' => false,
                'default' => 'active',
                'after' => 'web_url',
            ])
            ->update();
    }
}
