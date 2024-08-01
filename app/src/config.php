<?php

use App\Application\Command;
use App\Application\ExitUseCase;
use App\Application\GitLab\SyncGitLabEventsUseCase;
use App\Application\GitLab\SyncGitLabMergeRequestsUseCase;
use App\Application\GitLab\SyncGitLabProjectsUseCase;
use App\Application\GitLab\SyncGitLabUsersUseCase;
use App\Application\MenuUseCase;
use App\Domain\GitLab\Common\Repository\GitLabApiClientInterface;
use App\Domain\GitLab\Event\EventFactory;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;
use App\Domain\GitLab\Member\MemberFactory;
use App\Domain\GitLab\Member\Repository\GitLabApiMemberRepositoryInterface;
use App\Domain\GitLab\Member\Repository\GitLabDataBaseMemberRepositoryInterface;
use App\Domain\GitLab\MergeRequest\MergeRequestFactory;
use App\Domain\GitLab\MergeRequest\Repository\GitLabApiMergeRequestRepositoryInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabDataBaseMergeRequestRepositoryInterface;
use App\Domain\GitLab\Project\ProjectFactory;
use App\Domain\GitLab\Project\Repository\GitLabApiProjectRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;
use App\Infrastructure\GitLab\GitLabApiClient;
use App\Infrastructure\GitLab\GitLabApiEventRepository;
use App\Infrastructure\GitLab\GitLabApiMemberRepository;
use App\Infrastructure\GitLab\GitLabApiMergeRequestRepository;
use App\Infrastructure\GitLab\GitLabApiProjectRepository;
use App\Infrastructure\GitLab\GitLabMySqlMemberRepository;
use App\Infrastructure\GitLab\GitLabMySqlMergeRequestRepository;
use App\Infrastructure\GitLab\GitLabMySqlProjectRepository;
use Psr\Container\ContainerInterface;

return [
    'GITLAB_URL' => getenv('GITLAB_URL'),
    'GITLAB_TOKEN' => getenv('GITLAB_TOKEN'),
    'GITLAB_GROUP_ID' => getenv('GITLAB_GROUP_ID'),
    'GITLAB_URI' => function (ContainerInterface $c) {
        return $c->get('GITLAB_URL') . '/api/v4/';
    },

    'MYSQL_URL' => getenv('MYSQL_URL'),
    'MYSQL_DATABASE' => getenv('MYSQL_DATABASE'),
    'MYSQL_USER' => getenv('MYSQL_USER'),
    'MYSQL_USER_PASS' => getenv('MYSQL_USER_PASS'),
    'MYSQL_DSN' => function (ContainerInterface $c) {
        return 'mysql:host=' . $c->get('MYSQL_URL') . ';'
            . 'dbname=' . $c->get('MYSQL_DATABASE');
    },
    PDO::class => function (ContainerInterface $c) {
        return new PDO(
            $c->get('MYSQL_DSN'),
            $c->get('MYSQL_USER'),
            $c->get('MYSQL_USER_PASS')
        );
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
    Command::sync_gitlab_merge_requests->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitLabMergeRequestsUseCase::class);
    },
    Command::sync_gitlab_events->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitLabEventsUseCase::class);
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
    SyncGitLabMergeRequestsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitLabMergeRequestsUseCase(
            $c->get(GitLabApiMergeRequestRepositoryInterface::class),
            $c->get(GitLabDataBaseMergeRequestRepositoryInterface::class),
        );
    },
    SyncGitLabEventsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitLabEventsUseCase(
            $c->get(GitLabApiEventRepositoryInterface::class),
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
    GitLabApiMergeRequestRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabApiMergeRequestRepository(
            $c->get(GitLabApiClientInterface::class),
            new MergeRequestFactory()
        );
    },
    GitLabApiEventRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabApiEventRepository(
            $c->get(GitLabApiClientInterface::class),
            new EventFactory()
        );
    },
    GitLabApiClientInterface::class => function (ContainerInterface $c) {
        return new GitLabApiClient(
            $c->get('GITLAB_URI'),
            $c->get('GITLAB_TOKEN'),
            $c->get('GITLAB_GROUP_ID')
        );
    },
    GitLabDataBaseProjectRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabMySqlProjectRepository(
            $c->get(PDO::class)
        );
    },
    GitLabDataBaseMemberRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabMySqlMemberRepository(
            $c->get(PDO::class)
        );
    },
    GitLabDataBaseMergeRequestRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitLabMySqlMergeRequestRepository(
            $c->get(PDO::class)
        );
    }
];
