<?php

use App\Application\ExitUseCase;
use App\Application\GitLab\SyncGitLabProjects;
use App\Application\GitLab\SyncGitLabUsers;
use Psr\Container\ContainerInterface;

return [
    'GITLAB_URL' => getenv('GITLAB_URL'),
    'GITLAB_TOKEN' => getenv('GITLAB_TOKEN'),
    'GITLAB_GROUP_ID' => getenv('GITLAB_GROUP_ID'),

    'command_exit' => function (ContainerInterface $c) {
        return $c->get(ExitUseCase::class);
    },
    'command_sync_gitlab_projects' => function (ContainerInterface $c) {
        return $c->get(SyncGitLabProjects::class);
    },
    'command_sync_gitlab_users' => function (ContainerInterface $c) {
        return $c->get(SyncGitLabUsers::class);
    },

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
