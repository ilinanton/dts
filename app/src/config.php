<?php

use App\Application\Command;
use App\Application\ExitUseCase;
use App\Application\GitLab\SyncGitLabProjects;
use App\Application\GitLab\SyncGitLabUsers;
use App\Application\MenuUseCase;
use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\Common\Repository\GitLabApiInterface;
use App\Infrastructure\GitLab\GitLabApi;
use App\Infrastructure\GitLab\GitLabApiClient;
use Psr\Container\ContainerInterface;

return [
    'GITLAB_URL' => getenv('GITLAB_URL'),
    'GITLAB_TOKEN' => getenv('GITLAB_TOKEN'),
    'GITLAB_GROUP_ID' => getenv('GITLAB_GROUP_ID'),
    'GITLAB_GROUP_URI' => function (ContainerInterface $c) {
        return $c->get('GITLAB_URL')
            . '/api/v4/groups/'
            . $c->get('GITLAB_GROUP_ID') . '/';
    },

    Command::menu->diId() => function (ContainerInterface $c) {
        return $c->get(MenuUseCase::class);
    },
    Command::exit->diId() => function (ContainerInterface $c) {
        return $c->get(ExitUseCase::class);
    },
    Command::sync_gitlab_projects->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitLabProjects::class);
    },
    Command::sync_gitlab_users->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitLabUsers::class);
    },

    MenuUseCase::class => function (ContainerInterface $c) {
        return new MenuUseCase();
    },
    ExitUseCase::class => function (ContainerInterface $c) {
        return new ExitUseCase();
    },
    SyncGitLabProjects::class => function (ContainerInterface $c) {
        return new SyncGitLabProjects($c->get(GitLabApiInterface::class));
    },
    SyncGitLabUsers::class => function (ContainerInterface $c) {
        return new SyncGitLabUsers($c->get(GitLabApiInterface::class));
    },

    GitLabApiInterface::class => function (ContainerInterface $c) {
        return new GitLabApi($c->get(GitLabApiClientInterface::class));
    },
    GitLabApiClientInterface::class => function (ContainerInterface $c) {
        return new GitLabApiClient($c->get('GITLAB_GROUP_URI'), $c->get('GITLAB_TOKEN'));
    }
];
