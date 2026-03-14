<?php

declare(strict_types=1);

use App\Application\Common\Paginator;
use App\Application\Gitlab\SyncGitlabDataUseCase;
use App\Application\Gitlab\SyncGitlabMergeRequestLabelEventsUseCase;
use App\Application\Gitlab\SyncGitlabLabelsUseCase;
use App\Domain\Gitlab\Event\EventFilter;
use App\Domain\Gitlab\Event\EventFilterCollection;
use App\Application\Gitlab\SyncGitlabProjectCommitStatsUseCase;
use App\Application\Gitlab\SyncGitlabProjectCommitsUseCase;
use App\Application\Gitlab\SyncGitlabProjectEventsUseCase;
use App\Application\Gitlab\SyncGitlabProjectMergeRequestsUseCase;
use App\Application\Gitlab\SyncGitlabProjectsUseCase;
use App\Application\Gitlab\SyncGitlabUserEventsUseCase;
use App\Application\Gitlab\SyncGitlabUsersUseCase;
use App\Application\Report\DevReportUseCase;
use App\Application\Report\ReportDateProviderInterface;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseCollection;
use App\Application\UseCaseInterface;
use App\Domain\Git\Commit\CommitSinceDate;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Report\Repository\DevReportRepositoryInterface;
use App\Domain\Report\ScoringConfiguration;
use App\Domain\Report\ValueObject\LabelName;
use App\Domain\Report\ValueObject\ScoringPenalty;
use App\Domain\Report\ValueObject\ScoringWeight;
use App\Domain\Report\ScoringService;
use App\Infrastructure\Report\DevReportMySqlRepository;
use App\Application\Report\DevReportPresenterInterface;
use App\Presentation\Cli\StdoutSyncOutput;
use App\Presentation\Report\CliReportDateProvider;
use App\Presentation\Report\DevReportTablePresenter;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Domain\Gitlab\Commit\Repository\GitlabApiCommitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabDataBaseCommitRepositoryInterface;
use App\Domain\Gitlab\CommitStats\Repository\GitlabDataBaseCommitStatsRepositoryInterface;
use App\Infrastructure\Gitlab\GitlabApiClientCommitInterface;
use App\Infrastructure\Gitlab\GitlabApiClientEventInterface;
use App\Infrastructure\Gitlab\GitlabApiClientLabelInterface;
use App\Infrastructure\Gitlab\GitlabApiClientMergeRequestInterface;
use App\Infrastructure\Gitlab\GitlabApiClientProjectInterface;
use App\Infrastructure\Gitlab\GitlabApiClientResourceLabelEventInterface;
use App\Infrastructure\Gitlab\GitlabApiClientUserInterface;
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
use App\Domain\Git\Commit\CommitFactory;
use App\Domain\Git\Stats\StatsFactory;
use App\Domain\Gitlab\CommitStats\CommitStatsFactory;
use App\Domain\Gitlab\Event\EventFactory;
use App\Domain\Gitlab\Note\NoteFactory;
use App\Domain\Gitlab\PushData\Factory\PushDataFromArray;
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

    SyncOutputInterface::class => function (): SyncOutputInterface {
        return new StdoutSyncOutput();
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
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(60),
        );
    },
    EventFilterCollection::class => function (): EventFilterCollection {
        $collection = new EventFilterCollection();
        $collection->add(new EventFilter('action', 'pushed'));
        $collection->add(new EventFilter('action', 'commented'));
        $collection->add(new EventFilter('target_type', 'merge_request'));

        return $collection;
    },
    SyncGitlabProjectEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectEventsUseCase(
            new SyncDateAfter($c->get('GITLAB_SYNC_DATE_AFTER')),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabApiEventRepositoryInterface::class),
            $c->get(GitlabDataBaseEventRepositoryInterface::class),
            $c->get(EventFilterCollection::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabProjectCommitsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectCommitsUseCase(
            new SyncDateAfter($c->get('GITLAB_SYNC_DATE_AFTER')),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabApiCommitRepositoryInterface::class),
            $c->get(GitlabDataBaseCommitRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabProjectCommitStatsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectCommitStatsUseCase(
            new CommitSinceDate($c->get('GITLAB_SYNC_DATE_AFTER')),
            $c->get(GitRepositoryInterface::class),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(GitlabDataBaseCommitStatsRepositoryInterface::class),
            $c->get(CommitStatsFactory::class),
            $c->get(SyncOutputInterface::class),
        );
    },
    SyncGitlabUsersUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabUsersUseCase(
            $c->get(GitlabApiUserRepositoryInterface::class),
            $c->get(GitlabDataBaseUserRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabLabelsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabLabelsUseCase(
            $c->get(GitlabApiLabelRepositoryInterface::class),
            $c->get(GitlabDataBaseLabelRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(60),
        );
    },
    SyncGitlabMergeRequestLabelEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabMergeRequestLabelEventsUseCase(
            new SyncDateAfter($c->get('GITLAB_SYNC_DATE_AFTER')),
            $c->get(GitlabApiResourceLabelEventRepositoryInterface::class),
            $c->get(GitlabDataBaseResourceLabelEventRepositoryInterface::class),
            $c->get(GitlabDataBaseMergeRequestRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(60),
        );
    },
    'REPORT_TESTED_LABELS' => function (): array {
        $value = trim($_ENV['REPORT_TESTED_LABELS'] ?? '');
        if ($value === '') {
            return [];
        }
        return array_map(
            static fn(string $name): LabelName => new LabelName(trim($name)),
            explode(',', $value),
        );
    },
    ScoringConfiguration::class => function (): ScoringConfiguration {
        return new ScoringConfiguration(
            mergeRequestCreated: new ScoringWeight((float)$_ENV['POINTS_MERGE_REQUEST_CREATED']),
            approvalsGiven: new ScoringWeight((float)$_ENV['POINTS_APPROVALS_GIVEN']),
            mergeRequestMerged: new ScoringWeight((float)$_ENV['POINTS_MERGE_REQUEST_MERGED']),
            mergeRequestApproved: new ScoringWeight((float)$_ENV['POINTS_MERGE_REQUEST_APPROVED']),
            mergeRequestTested: new ScoringWeight((float)$_ENV['POINTS_MERGE_REQUEST_TESTED']),
            linesAdded: new ScoringWeight((float)$_ENV['POINTS_LINES_ADDED']),
            linesRemoved: new ScoringWeight((float)$_ENV['POINTS_LINES_REMOVED']),
            selfApprovals: new ScoringPenalty((float)$_ENV['POINTS_SELF_APPROVALS']),
            directCommitsToMain: new ScoringPenalty((float)$_ENV['POINTS_DIRECT_COMMITS_TO_MAIN']),
        );
    },
    ScoringService::class => function (ContainerInterface $c): ScoringService {
        return new ScoringService(
            $c->get(ScoringConfiguration::class),
        );
    },
    DevReportRepositoryInterface::class => function (ContainerInterface $c): DevReportRepositoryInterface {
        return new DevReportMySqlRepository(
            $c->get(PDO::class),
        );
    },
    DevReportPresenterInterface::class => function (): DevReportPresenterInterface {
        return new DevReportTablePresenter(
            new ConsoleOutput(),
        );
    },
    ReportDateProviderInterface::class => function (): ReportDateProviderInterface {
        return new CliReportDateProvider();
    },
    DevReportUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new DevReportUseCase(
            $c->get(DevReportRepositoryInterface::class),
            $c->get(ScoringService::class),
            $c->get(DevReportPresenterInterface::class),
            $c->get(ReportDateProviderInterface::class),
            $c->get('REPORT_TESTED_LABELS'),
        );
    },
    SyncGitlabProjectMergeRequestsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectMergeRequestsUseCase(
            new SyncDateAfter($c->get('GITLAB_SYNC_DATE_AFTER')),
            $c->get(GitlabApiMergeRequestRepositoryInterface::class),
            $c->get(GitlabDataBaseMergeRequestRepositoryInterface::class),
            $c->get(GitlabDataBaseProjectRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabUserEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabUserEventsUseCase(
            new SyncDateAfter($c->get('GITLAB_SYNC_DATE_AFTER')),
            $c->get(GitlabDataBaseUserRepositoryInterface::class),
            $c->get(GitlabApiEventRepositoryInterface::class),
            $c->get(GitlabDataBaseEventRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(20),
        );
    },

    GitlabApiProjectRepositoryInterface::class => function (ContainerInterface $c): GitlabApiProjectRepositoryInterface {
        return new GitlabApiProjectRepository(
            $c->get(GitlabApiClientProjectInterface::class),
            $c->get('GITLAB_EXCLUDED_PROJECT_IDS'),
        );
    },
    GitlabApiUserRepositoryInterface::class => function (ContainerInterface $c): GitlabApiUserRepositoryInterface {
        return new GitlabApiUserRepository(
            $c->get(GitlabApiClientUserInterface::class),
            $c->get('GITLAB_EXCLUDED_USER_IDS'),
        );
    },
    GitlabApiLabelRepositoryInterface::class => function (ContainerInterface $c): GitlabApiLabelRepositoryInterface {
        return new GitlabApiLabelRepository(
            $c->get(GitlabApiClientLabelInterface::class),
        );
    },
    GitlabApiResourceLabelEventRepositoryInterface::class => function (ContainerInterface $c): GitlabApiResourceLabelEventRepositoryInterface {
        return new GitlabApiResourceLabelEventRepository(
            $c->get(GitlabApiClientResourceLabelEventInterface::class),
        );
    },
    GitlabApiMergeRequestRepositoryInterface::class => function (ContainerInterface $c): GitlabApiMergeRequestRepositoryInterface {
        return new GitlabApiMergeRequestRepository(
            $c->get(GitlabApiClientMergeRequestInterface::class),
        );
    },
    EventFactory::class => function (ContainerInterface $c): EventFactory {
        return new EventFactory(
            new PushDataFromArray(),
            new NoteFactory(),
        );
    },
    CommitFactory::class => function (ContainerInterface $c): CommitFactory {
        return new CommitFactory(
            new StatsFactory(),
        );
    },
    CommitStatsFactory::class => function (): CommitStatsFactory {
        return new CommitStatsFactory();
    },
    GitlabApiEventRepositoryInterface::class => function (ContainerInterface $c): GitlabApiEventRepositoryInterface {
        return new GitlabApiEventRepository(
            $c->get(GitlabApiClientEventInterface::class),
            $c->get(EventFactory::class),
        );
    },
    GitRepositoryInterface::class => function (ContainerInterface $c): GitRepositoryInterface {
        return new GitRepository(
            $c->get('GIT_LOG_EXCLUDE_PATH'),
            $c->get(CommitFactory::class),
        );
    },
    GitlabApiCommitRepositoryInterface::class => function (ContainerInterface $c): GitlabApiCommitRepositoryInterface {
        return new GitlabApiCommitRepository(
            $c->get(GitlabApiClientCommitInterface::class),
        );
    },
    GitlabApiClient::class => function (ContainerInterface $c): GitlabApiClient {
        return new GitlabApiClient(
            $c->get('GITLAB_URI'),
            $c->get('GITLAB_TOKEN'),
            $c->get('GITLAB_GROUP_ID'),
        );
    },
    GitlabApiClientProjectInterface::class => function (ContainerInterface $c): GitlabApiClientProjectInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabApiClientUserInterface::class => function (ContainerInterface $c): GitlabApiClientUserInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabApiClientMergeRequestInterface::class => function (ContainerInterface $c): GitlabApiClientMergeRequestInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabApiClientEventInterface::class => function (ContainerInterface $c): GitlabApiClientEventInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabApiClientCommitInterface::class => function (ContainerInterface $c): GitlabApiClientCommitInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabApiClientLabelInterface::class => function (ContainerInterface $c): GitlabApiClientLabelInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabApiClientResourceLabelEventInterface::class => function (ContainerInterface $c): GitlabApiClientResourceLabelEventInterface {
        return $c->get(GitlabApiClient::class);
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
