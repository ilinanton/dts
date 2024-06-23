<?php

use App\Application\Command;
use App\Application\ExitUseCase;
use App\Application\GitLab\SyncGitLabProjectsUseCase;
use App\Application\GitLab\SyncGitLabUsersUseCase;
use App\Application\MenuUseCase;
use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\Member\MemberFactory;
use App\Domain\GitLab\Member\Repository\GitLabApiMemberRepositoryInterface;
use App\Domain\GitLab\Member\Repository\GitLabDataBaseMemberRepositoryInterface;
use App\Domain\GitLab\Project\ProjectFactory;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;
use App\Infrastructure\GitLab\GitLabApiClient;
use App\Infrastructure\GitLab\GitLabApiMemberRepository;
use App\Infrastructure\GitLab\GitLabApiProjectRepository;
use App\Infrastructure\GitLab\GitLabMySqlMemberRepository;
use App\Infrastructure\GitLab\GitLabMySqlProjectRepository;
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

    'MYSQL_URL' => getenv('MYSQL_URL'),
    'MYSQL_DATABASE' => getenv('MYSQL_DATABASE'),
    'MYSQL_USER' => getenv('MYSQL_USER'),
    'MYSQL_USER_PASS' => getenv('MYSQL_USER_PASS'),
    'MYSQL_DSN' => function (ContainerInterface $c) {
        return 'mysql:host=' . $c->get('MYSQL_URL') . ';'
            . 'dbname=' . $c->get('MYSQL_DATABASE');
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
        return new SyncGitLabProjectsUseCase(
            $c->get(GitLabApiProjectRepositoryInterface::class),
            $c->get(GitLabDataBaseProjectRepositoryInterface::class)
        );
    },
    SyncGitLabUsersUseCase::class => function (ContainerInterface $c) {
        return new SyncGitLabUsersUseCase(
            $c->get(GitLabApiMemberRepositoryInterface::class),
            $c->get(GitLabDataBaseMemberRepositoryInterface::class)
        );
    },

    GitLabApiProjectRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabApiProjectRepository(
            $c->get(GitLabApiClientInterface::class),
            new ProjectFactory()
        );
    },
    GitLabApiMemberRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabApiMemberRepository(
            $c->get(GitLabApiClientInterface::class),
            new MemberFactory()
        );
    },
    GitLabApiClientInterface::class => function (ContainerInterface $c) {
        return new GitLabApiClient(
            $c->get('GITLAB_GROUP_URI'),
            $c->get('GITLAB_TOKEN')
        );
    },
    GitLabDataBaseProjectRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabMySqlProjectRepository(
            $c->get('MYSQL_DSN'),
            $c->get('MYSQL_USER'),
            $c->get('MYSQL_USER_PASS')
        );
    },
    GitLabDataBaseMemberRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabMySqlMemberRepository(
            $c->get('MYSQL_DSN'),
            $c->get('MYSQL_USER'),
            $c->get('MYSQL_USER_PASS')
        );
    }
];
