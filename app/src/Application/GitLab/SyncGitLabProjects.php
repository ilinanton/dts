<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;

class SyncGitLabProjects implements UseCaseInterface
{
    public function __construct()
    {
    }

    public function execute(): void
    {
        echo 'SyncGitLabProjects!!!' . PHP_EOL;
    }
}
