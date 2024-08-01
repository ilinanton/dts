<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitLabEvent extends AbstractMigration
{
    private const TABLE_NAME = 'git_lab_event';

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
            ->addColumn('project_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('action_name', 'string', ['length' => 255, 'null' => false])
            ->addColumn('target_id', 'biginteger', ['signed' => false, 'null' => true])
            ->addColumn('target_iid', 'biginteger', ['signed' => false, 'null' => true])
            ->addColumn('target_type', 'string', ['length' => 255, 'null' => true])
            ->addColumn('author_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('target_title', 'string', ['length' => 255, 'null' => true])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
