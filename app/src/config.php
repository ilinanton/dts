<?php

use App\Application\Command;
use App\Application\ExitUseCase;
use App\Application\Git\SyncGitDataUseCase;
use App\Application\Gitlab\SyncGitlabDataUseCase;
use App\Application\Gitlab\SyncGitlabProjectCommitsUseCase;
use App\Application\Gitlab\SyncGitlabProjectEventsUseCase;
use App\Application\Gitlab\SyncGitlabUserEventsUseCase;
use App\Application\Gitlab\SyncGitlabProjectMergeRequestsUseCase;
use App\Application\Gitlab\SyncGitlabProjectsUseCase;
use App\Application\Gitlab\SyncGitlabUsersUseCase;
use App\Application\MenuUseCase;
use App\Application\UseCaseCollection;
use App\Domain\Gitlab\Commit\CommitFactory;
use App\Domain\Gitlab\Commit\Repository\GitlabApiCommitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabDataBaseCommitRepositoryInterface;
use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\Event\EventFactory;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use App\Domain\Gitlab\User\UserFactory;
use App\Domain\Gitlab\User\Repository\GitlabApiUserRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\MergeRequestFactory;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabApiMergeRequestRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\Project\ProjectFactory;
use App\Domain\Gitlab\Project\Repository\GitlabApiProjectRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;
use App\Infrastructure\Gitlab\GitlabApiClient;
use App\Infrastructure\Gitlab\GitlabApiCommitRepository;
use App\Infrastructure\Gitlab\GitlabApiEventRepository;
use App\Infrastructure\Gitlab\GitlabApiUserRepository;
use App\Infrastructure\Gitlab\GitlabApiMergeRequestRepository;
use App\Infrastructure\Gitlab\GitlabApiProjectRepository;
use App\Infrastructure\Gitlab\GitlabMySqlCommitRepository;
use App\Infrastructure\Gitlab\GitlabMySqlEventRepository;
use App\Infrastructure\Gitlab\GitlabMySqlUserRepository;
use App\Infrastructure\Gitlab\GitlabMySqlMergeRequestRepository;
use App\Infrastructure\Gitlab\GitlabMySqlProjectRepository;
use Psr\Container\ContainerInterface;

return [
    'GITLAB_URL' => getenv('GITLAB_URL'),
    'GITLAB_TOKEN' => getenv('GITLAB_TOKEN'),
    'GITLAB_GROUP_ID' => getenv('GITLAB_GROUP_ID'),
    'GITLAB_SYNC_DATE_AFTER' => getenv('GITLAB_SYNC_DATE_AFTER'),
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
    Command::sync_gitlab_data->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabDataUseCase::class);
    },
    Command::sync_gitlab_projects->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabProjectsUseCase::class);
    },
    Command::sync_gitlab_project_events->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabProjectEventsUseCase::class);
    },
    Command::sync_gitlab_project_commits->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabProjectCommitsUseCase::class);
    },
    Command::sync_gitlab_users->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabUsersUseCase::class);
    },
    Command::sync_gitlab_merge_requests->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabProjectMergeRequestsUseCase::class);
    },
    Command::sync_gitlab_user_events->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabUserEventsUseCase::class);
    },
    Command::sync_git_data->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitDataUseCase::class);
    },

    MenuUseCase::class => function (ContainerInterface $c) {
        return new MenuUseCase();
    },
    ExitUseCase::class => function (ContainerInterface $c) {
        return new ExitUseCase();
    },

    SyncGitlabDataUseCase::class => function (ContainerInterface $c) {
        $useCaseCollection = new UseCaseCollection();
        $useCaseCollection->add($c->get(SyncGitlabProjectsUseCase::class));
        $useCaseCollection->add($c->get(SyncGitlabUsersUseCase::class));
        $useCaseCollection->add($c->get(SyncGitlabProjectMergeRequestsUseCase::class));
        $useCaseCollection->add($c->get(SyncGitlabProjectEventsUseCase::class));

        return new SyncGitlabDataUseCase($useCaseCollection);
    },
    SyncGitlabProjectsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitlabProjectsUseCase(
            $c->get(GitlabApiProjectRepositoryInterface::class),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class)
        );
    },
    SyncGitlabProjectEventsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitlabProjectEventsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabApiEventRepositoryInterface::class),
            $c->get(GitlabDataBaseEventRepositoryInterface::class),
        );
    },
    SyncGitlabProjectCommitsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitlabProjectCommitsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabApiCommitRepositoryInterface::class),
            $c->get(GitlabDataBaseCommitRepositoryInterface::class),
        );
    },
    SyncGitlabUsersUseCase::class => function (ContainerInterface $c) {
        return new SyncGitlabUsersUseCase(
            $c->get(GitlabApiUserRepositoryInterface::class),
            $c->get(GitlabDataBaseUserRepositoryInterface::class)
        );
    },
    SyncGitlabProjectMergeRequestsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitlabProjectMergeRequestsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabApiMergeRequestRepositoryInterface::class),
            $c->get(GitlabDataBaseMergeRequestRepositoryInterface::class),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class)
        );
    },
    SyncGitlabUserEventsUseCase::class => function (ContainerInterface $c) {
        return new SyncGitlabUserEventsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabDataBaseUserRepositoryInterface::class),
            $c->get(GitlabApiEventRepositoryInterface::class),
            $c->get(GitlabDataBaseEventRepositoryInterface::class),
        );
    },
    SyncGitDataUseCase::class => function (ContainerInterface $c) {
        return new SyncGitDataUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
        );
    },

    GitlabApiProjectRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabApiProjectRepository(
            $c->get(GitlabApiClientInterface::class),
            new ProjectFactory()
        );
    },
    GitlabApiUserRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabApiUserRepository(
            $c->get(GitlabApiClientInterface::class),
            new UserFactory()
        );
    },
    GitlabApiMergeRequestRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabApiMergeRequestRepository(
            $c->get(GitlabApiClientInterface::class),
            new MergeRequestFactory()
        );
    },
    GitlabApiEventRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabApiEventRepository(
            $c->get(GitlabApiClientInterface::class),
            new EventFactory()
        );
    },
    GitlabApiCommitRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabApiCommitRepository(
            $c->get(GitlabApiClientInterface::class),
            new CommitFactory()
        );
    },
    GitlabApiClientInterface::class => function (ContainerInterface $c) {
        return new GitlabApiClient(
            $c->get('GITLAB_URI'),
            $c->get('GITLAB_TOKEN'),
            $c->get('GITLAB_GROUP_ID')
        );
    },
    GitlabDataBaseProjectRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabMySqlProjectRepository(
            $c->get(PDO::class),
            new ProjectFactory()
        );
    },
    GitlabDataBaseUserRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabMySqlUserRepository(
            $c->get(PDO::class),
            new UserFactory()
        );
    },
    GitlabDataBaseMergeRequestRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabMySqlMergeRequestRepository(
            $c->get(PDO::class)
        );
    },
    GitlabDataBaseEventRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabMySqlEventRepository(
            $c->get(PDO::class)
        );
    },
    GitlabDataBaseCommitRepositoryInterface::class => function (ContainerInterface $c) {
        return new GitlabMySqlCommitRepository(
            $c->get(PDO::class)
        );
    },
];
