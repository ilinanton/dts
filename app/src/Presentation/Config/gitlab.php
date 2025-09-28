<?php

declare(strict_types=1);

use App\Application\Gitlab\SyncGitlabDataUseCase;
use App\Application\Gitlab\SyncGitlabMergeRequestLabelEventsUseCase;
use App\Application\Gitlab\SyncGitlabLabelsUseCase;
use App\Application\Gitlab\SyncGitlabProjectCommitStatsUseCase;
use App\Application\Gitlab\SyncGitlabProjectCommitsUseCase;
use App\Application\Gitlab\SyncGitlabProjectEventsUseCase;
use App\Application\Gitlab\SyncGitlabProjectMergeRequestsUseCase;
use App\Application\Gitlab\SyncGitlabProjectsUseCase;
use App\Application\Gitlab\SyncGitlabUserEventsUseCase;
use App\Application\Gitlab\SyncGitlabUsersUseCase;
use App\Application\Report\DevReportUseCase;
use App\Application\UseCaseCollection;
use App\Application\UseCaseInterface;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabApiCommitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabDataBaseCommitRepositoryInterface;
use App\Domain\Gitlab\CommitStats\Repository\GitlabDataBaseCommitStatsRepositoryInterface;
use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use App\Domain\Gitlab\Label\Repository\GitlabApiLabelRepositoryInterface;
use App\Domain\Gitlab\Label\Repository\GitlabDataBaseLabelRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabApiMergeRequestRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabDataBaseMergeRequestRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabApiProjectRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabDataBaseProjectRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabApiResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabDataBaseResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabApiUserRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;
use App\Infrastructure\Git\GitRepository;
use App\Infrastructure\Gitlab\GitlabApiClient;
use App\Infrastructure\Gitlab\GitlabApiCommitRepository;
use App\Infrastructure\Gitlab\GitlabApiEventRepository;
use App\Infrastructure\Gitlab\GitlabApiLabelRepository;
use App\Infrastructure\Gitlab\GitlabApiMergeRequestRepository;
use App\Infrastructure\Gitlab\GitlabApiProjectRepository;
use App\Infrastructure\Gitlab\GitlabApiResourceLabelEventRepository;
use App\Infrastructure\Gitlab\GitlabApiUserRepository;
use App\Infrastructure\Gitlab\GitlabMySqlCommitRepository;
use App\Infrastructure\Gitlab\GitlabMySqlCommitStatsRepository;
use App\Infrastructure\Gitlab\GitlabMySqlEventRepository;
use App\Infrastructure\Gitlab\GitlabMySqlLabelRepository;
use App\Infrastructure\Gitlab\GitlabMySqlMergeRequestRepository;
use App\Infrastructure\Gitlab\GitlabMySqlProjectRepository;
use App\Infrastructure\Gitlab\GitlabMySqlResourceLabelEventRepository;
use App\Infrastructure\Gitlab\GitlabMySqlUserRepository;
use Psr\Container\ContainerInterface;

return [
    'GITLAB_URL' => $_ENV['GITLAB_URL'],
    'GITLAB_TOKEN' => $_ENV['GITLAB_TOKEN'],
    'GITLAB_GROUP_ID' => (int)$_ENV['GITLAB_GROUP_ID'],
    'GITLAB_SYNC_DATE_AFTER' => $_ENV['GITLAB_SYNC_DATE_AFTER'],
    'GITLAB_EXCLUDED_PROJECT_IDS' => function (ContainerInterface $c): array {
        $ids = explode(',', $_ENV['GITLAB_EXCLUDED_PROJECT_IDS']);
        array_walk($ids, function (&$value): void {
            $value = (int)trim($value);
        });
        return $ids;
    },
    'GITLAB_EXCLUDED_USER_IDS' => function (ContainerInterface $c): array {
        $ids = explode(',', $_ENV['GITLAB_EXCLUDED_USER_IDS']);
        array_walk($ids, function (&$value): void {
            $value = (int)trim($value);
        });
        return $ids;
    },
    'GITLAB_URI' => function (ContainerInterface $c): string {
        return $c->get('GITLAB_URL') . '/api/v4/';
    },

    'GIT_LOG_EXCLUDE_PATH' => function () {
        return explode(',', $_ENV['GIT_LOG_EXCLUDE_PATH']);
    },

    SyncGitlabDataUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        $useCaseCollection = new UseCaseCollection();
        $useCaseCollection->add($c->get(SyncGitlabProjectsUseCase::class));
        $useCaseCollection->add($c->get(SyncGitlabUsersUseCase::class));
        $useCaseCollection->add($c->get(SyncGitlabLabelsUseCase::class));

        return new SyncGitlabDataUseCase($useCaseCollection);
    },
    SyncGitlabProjectsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectsUseCase(
            $c->get(GitlabApiProjectRepositoryInterface::class),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class)
        );
    },
    SyncGitlabProjectEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectEventsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabApiEventRepositoryInterface::class),
            $c->get(GitlabDataBaseEventRepositoryInterface::class),
        );
    },
    SyncGitlabProjectCommitsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectCommitsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabApiCommitRepositoryInterface::class),
            $c->get(GitlabDataBaseCommitRepositoryInterface::class),
        );
    },
    SyncGitlabProjectCommitStatsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectCommitStatsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitRepositoryInterface::class),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabDataBaseCommitStatsRepositoryInterface::class),
        );
    },
    SyncGitlabUsersUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabUsersUseCase(
            $c->get(GitlabApiUserRepositoryInterface::class),
            $c->get(GitlabDataBaseUserRepositoryInterface::class),
        );
    },
    SyncGitlabLabelsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabLabelsUseCase(
            $c->get(GitlabApiLabelRepositoryInterface::class),
            $c->get(GitlabDataBaseLabelRepositoryInterface::class),
        );
    },
    SyncGitlabMergeRequestLabelEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabMergeRequestLabelEventsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabApiResourceLabelEventRepositoryInterface::class),
            $c->get(GitlabDataBaseResourceLabelEventRepositoryInterface::class),
            $c->get(GitlabDataBaseMergeRequestRepositoryInterface::class),
        );
    },
    DevReportUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new DevReportUseCase(
            $c->get(PDO::class),
        );
    },
    SyncGitlabProjectMergeRequestsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectMergeRequestsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabApiMergeRequestRepositoryInterface::class),
            $c->get(GitlabDataBaseMergeRequestRepositoryInterface::class),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
        );
    },
    SyncGitlabUserEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabUserEventsUseCase(
            $c->get('GITLAB_SYNC_DATE_AFTER'),
            $c->get(GitlabDataBaseUserRepositoryInterface::class),
            $c->get(GitlabApiEventRepositoryInterface::class),
            $c->get(GitlabDataBaseEventRepositoryInterface::class),
        );
    },

    GitlabApiProjectRepositoryInterface::class => function (ContainerInterface $c): GitlabApiProjectRepositoryInterface {
        return new GitlabApiProjectRepository(
            $c->get(GitlabApiClientInterface::class),
            $c->get('GITLAB_EXCLUDED_PROJECT_IDS'),
        );
    },
    GitlabApiUserRepositoryInterface::class => function (ContainerInterface $c): GitlabApiUserRepositoryInterface {
        return new GitlabApiUserRepository(
            $c->get(GitlabApiClientInterface::class),
            $c->get('GITLAB_EXCLUDED_USER_IDS'),
        );
    },
    GitlabApiLabelRepositoryInterface::class => function (ContainerInterface $c): GitlabApiLabelRepositoryInterface {
        return new GitlabApiLabelRepository(
            $c->get(GitlabApiClientInterface::class),
        );
    },
    GitlabApiResourceLabelEventRepositoryInterface::class => function (ContainerInterface $c): GitlabApiResourceLabelEventRepositoryInterface {
        return new GitlabApiResourceLabelEventRepository(
            $c->get(GitlabApiClientInterface::class),
        );
    },
    GitlabApiMergeRequestRepositoryInterface::class => function (ContainerInterface $c): GitlabApiMergeRequestRepositoryInterface {
        return new GitlabApiMergeRequestRepository(
            $c->get(GitlabApiClientInterface::class),
        );
    },
    GitlabApiEventRepositoryInterface::class => function (ContainerInterface $c): GitlabApiEventRepositoryInterface {
        return new GitlabApiEventRepository(
            $c->get(GitlabApiClientInterface::class),
        );
    },
    GitRepositoryInterface::class => function (ContainerInterface $c): GitRepositoryInterface {
        return new GitRepository(
            $c->get('GIT_LOG_EXCLUDE_PATH'),
        );
    },
    GitlabApiCommitRepositoryInterface::class => function (ContainerInterface $c): GitlabApiCommitRepositoryInterface {
        return new GitlabApiCommitRepository(
            $c->get(GitlabApiClientInterface::class),
        );
    },
    GitlabApiClientInterface::class => function (ContainerInterface $c): GitlabApiClientInterface {
        return new GitlabApiClient(
            $c->get('GITLAB_URI'),
            $c->get('GITLAB_TOKEN'),
            $c->get('GITLAB_GROUP_ID'),
        );
    },
    GitlabDataBaseProjectRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseProjectRepositoryInterface {
        return new GitlabMySqlProjectRepository(
            $c->get(PDO::class),
        );
    },
    GitlabDataBaseUserRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseUserRepositoryInterface {
        return new GitlabMySqlUserRepository(
            $c->get(PDO::class),
        );
    },
    GitlabDataBaseLabelRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseLabelRepositoryInterface {
        return new GitlabMySqlLabelRepository(
            $c->get(PDO::class),
        );
    },
    GitlabDataBaseResourceLabelEventRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseResourceLabelEventRepositoryInterface {
        return new GitlabMySqlResourceLabelEventRepository(
            $c->get(PDO::class),
        );
    },
    GitlabDataBaseMergeRequestRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseMergeRequestRepositoryInterface {
        return new GitlabMySqlMergeRequestRepository(
            $c->get(PDO::class),
        );
    },
    GitlabDataBaseEventRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseEventRepositoryInterface {
        return new GitlabMySqlEventRepository(
            $c->get(PDO::class),
        );
    },
    GitlabDataBaseCommitRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseCommitRepositoryInterface {
        return new GitlabMySqlCommitRepository(
            $c->get(PDO::class),
        );
    },
    GitlabDataBaseCommitStatsRepositoryInterface::class => function (ContainerInterface $c): GitlabDataBaseCommitStatsRepositoryInterface {
        return new GitlabMySqlCommitStatsRepository(
            $c->get(PDO::class),
        );
    },
];
