<?php

use App\Application\Command;
use App\Application\ExitUseCase;
use App\Application\GitLab\SyncGitLabProjectsUseCase;
use App\Application\GitLab\SyncGitLabUsersUseCase;
use App\Application\MenuUseCase;
use App\Domain\GitLab\Member\Repository\GitLabApiMemberRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;
use App\Infrastructure\GitLab\GitLabApiClient;
use App\Infrastructure\GitLab\GitLabApiClientInterface;
use App\Infrastructure\GitLab\GitLabApiProjectRepository;
use App\Infrastructure\GitLab\GitLabApiMemberRepository;
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
        return $c->get(SyncGitLabProjectsUseCase::class);
    },
    Command::sync_gitlab_users->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitLabUsersUseCase::class);
    },

    MenuUseCase::class => function (ContainerInterface $c) {
        return new MenuUseCase();
    },
    ExitUseCase::class => function (ContainerInterface $c) {
        return new ExitUseCase();
    },

    SyncGitLabProjectsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitLabProjectsUseCase($c->get(GitLabApiProjectRepositoryInterface::class));
    },
    SyncGitLabUsersUseCase::class => function (ContainerInterface $c) {
        return new SyncGitLabUsersUseCase($c->get(GitLabApiMemberRepositoryInterface::class));
    },

    GitLabApiProjectRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabApiProjectRepository($c->get(GitLabApiClientInterface::class));
    },
    GitLabApiMemberRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabApiMemberRepository($c->get(GitLabApiClientInterface::class));
    },
    GitLabApiClientInterface::class => function (ContainerInterface $c) {
        return new GitLabApiClient($c->get('GITLAB_GROUP_URI'), $c->get('GITLAB_TOKEN'));
    }
];
