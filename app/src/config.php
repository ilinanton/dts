<?php

use App\Application\ExitUseCase;
use App\Application\GitLab\SyncGitLabProjects;
use App\Application\GitLab\SyncGitLabUsers;
use Psr\Container\ContainerInterface;

return [
    'GITLAB_URL' => getenv('GITLAB_URL'),
    'GITLAB_TOKEN' => getenv('GITLAB_TOKEN'),
    'GITLAB_GROUP_ID' => getenv('GITLAB_GROUP_ID'),

    ExitUseCase::class => function (ContainerInterface $c) {
        return new ExitUseCase();
    },
    SyncGitLabProjects::class => function (ContainerInterface $c) {
        return new SyncGitLabProjects();
    },
    SyncGitLabUsers::class => function (ContainerInterface $c) {
        return new SyncGitLabUsers();
    },
];
