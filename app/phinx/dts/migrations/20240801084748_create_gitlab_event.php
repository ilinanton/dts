<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateGitlabEvent extends AbstractMigration
{
    private const TABLE_NAME = 'gitlab_event';

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

            ->addColumn('push_data_action', 'string', ['length' => 255, 'null' => true])
            ->addColumn('push_data_commit_title', 'string', ['length' => 255, 'null' => true])
            ->addColumn('push_data_commit_count', 'biginteger', ['signed' => false, 'null' => true])
            ->addColumn('push_data_commit_from', 'string', ['length' => 255, 'null' => true])
            ->addColumn('push_data_commit_to', 'string', ['length' => 255, 'null' => true])
            ->addColumn('push_data_ref', 'string', ['length' => 255, 'null' => true])
            ->addColumn('push_data_ref_count', 'biginteger', ['signed' => false, 'null' => true])
            ->addColumn('push_data_ref_type', 'string', ['length' => 255, 'null' => true])

            ->addColumn('note_body', 'text', ['null' => true, 'limit' => MysqlAdapter::TEXT_MEDIUM])

            ->addIndex(['project_id'])
            ->addIndex(['action_name'])
            ->addIndex(['target_id'])
            ->addIndex(['target_iid'])
            ->addIndex(['target_type'])
            ->addIndex(['author_id'])
            ->addIndex(['target_title'])
            ->addIndex(['created_at'])

            ->addIndex(['push_data_action'])
            ->addIndex(['push_data_commit_count'])
            ->addIndex(['push_data_commit_from'])
            ->addIndex(['push_data_commit_to'])
            ->addIndex(['push_data_ref'])
            ->addIndex(['push_data_ref_count'])
            ->addIndex(['push_data_ref_type'])

            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable(self::TABLE_NAME)) {
            $this->table(self::TABLE_NAME)->drop()->save();
        }
    }
}
