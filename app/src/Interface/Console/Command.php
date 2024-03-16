<?php

namespace App\Interface\Console;

use App\Application\ExitUseCase;
use App\Application\GitLab\SyncGitLabProjects;
use App\Application\GitLab\SyncGitLabUsers;

enum Command
{
    case Exit;
    case exit;
    case SyncGitLabProjects;
    case SyncGitLabUsers;

    public function useCaseClass(): string
    {
        return match ($this) {
            self::Exit, self::exit => ExitUseCase::class,
            self::SyncGitLabProjects => SyncGitLabProjects::class,
            self::SyncGitLabUsers => SyncGitLabUsers::class,
        };
    }
}
