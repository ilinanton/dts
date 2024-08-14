<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitLabMergeRequest extends AbstractMigration
{
    private const TABLE_NAME = 'git_lab_merge_request';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['id'],
            ]
        )
            ->addColumn('id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('iid', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('project_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('title', 'string', ['length' => 255, 'null' => false])
            ->addColumn('state', 'enum', [
                'values' => ['opened', 'closed', 'locked', 'merged'],
                'null' => false,
            ])
            ->addColumn('merged_at', 'datetime', ['null' => true])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addColumn('target_branch', 'string', ['length' => 63, 'null' => false])
            ->addColumn('source_branch', 'string', ['length' => 63, 'null' => false])
            ->addColumn('author_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('web_url', 'string', ['length' => 500, 'null' => false])
            ->addIndex(['iid'])
            ->addIndex(['project_id'])
            ->addIndex(['state'])
            ->addIndex(['merged_at'])
            ->addIndex(['created_at'])
            ->addIndex(['updated_at'])
            ->addIndex(['target_branch'])
            ->addIndex(['source_branch'])
            ->addIndex(['author_id'])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
