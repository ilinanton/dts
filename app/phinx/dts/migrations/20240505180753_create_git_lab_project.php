<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGitLabProject extends AbstractMigration
{
    private string $table = 'git_lab_project';

    public function up(): void
    {
        $this->table(
            $this->table,
            [
                'id' => false,
                'primary_key' => ['id'],
            ]
        )
            ->addColumn('id', 'biginteger', ['signed' => false, 'null' => false])
            ->addColumn('name', 'string', ['length' => 255, 'null' => false])
            ->addColumn('default_branch', 'string', ['length' => 255, 'null' => false])
            ->addColumn('web_url', 'string', ['length' => 500, 'null' => false])
            ->create();
    }

    public function down(): void
    {
        if ($this->hasTable($this->table)) {
            $this->table($this->table)->drop()->save();
        }
    }
}
