<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;

class SyncGitLabUsers implements UseCaseInterface
{
    public function __construct()
    {
    }

    public function execute(): void
    {
        echo 'SyncGitLabUsers!!!' . PHP_EOL;
    }
}
