<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableGitlabResourceLabelEvent extends AbstractMigration
{
    private const TABLE_NAME = 'gitlab_resource_label_event';

    public function up(): void
    {
        $this->table(
            self::TABLE_NAME,
            [
                'id' => false,
                'primary_key' => ['id'],
            ],
        )
            ->addColumn('id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('user_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addColumn('resource_type', 'string', ['length' => 32, 'null' => false])
            ->addColumn('resource_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('label_id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('action_name', 'string', ['length' => 32, 'null' => false])
            ->addColumn('project_id', 'biginteger', ['signed' => false, 'null' => true])
            ->addColumn('group_id', 'biginteger', ['signed' => false, 'null' => true])
            ->addIndex(['user_id'])
            ->addIndex(['created_at'])
            ->addIndex(['resource_type'])
            ->addIndex(['resource_id'])
            ->addIndex(['label_id'])
            ->addIndex(['action_name'])
            ->addIndex(['project_id'])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
