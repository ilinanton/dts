<?php

declare(strict_types=1);

use App\Application\Common\Paginator;
use App\Application\Gitlab\SyncGitlabDataUseCase;
use App\Application\Gitlab\SyncGitlabMergeRequestLabelEventsUseCase;
use App\Application\Gitlab\SyncGitlabLabelsUseCase;
use App\Domain\Gitlab\Event\EventFilter;
use App\Domain\Gitlab\Event\EventFilterCollection;
use App\Domain\Gitlab\Event\EventFilterParam;
use App\Domain\Gitlab\Event\EventFilterValue;
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
use App\Presentation\Report\DevReportHtmlPresenter;
use App\Presentation\Cli\StdoutSyncOutput;
use App\Presentation\Config\GitlabConfiguration;
use App\Presentation\Report\CliReportDateProvider;
use App\Presentation\Report\DevReportTablePresenter;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Domain\Gitlab\Commit\Repository\GitlabSourceCommitRepositoryInterface;
use App\Domain\Gitlab\Commit\Repository\GitlabStorageCommitRepositoryInterface;
use App\Domain\Gitlab\CommitStats\Repository\GitlabStorageCommitStatsRepositoryInterface;
use App\Domain\Gitlab\Source\GitlabSourceCommitInterface;
use App\Domain\Gitlab\Source\GitlabSourceEventInterface;
use App\Domain\Gitlab\Source\GitlabSourceLabelInterface;
use App\Domain\Gitlab\Source\GitlabSourceMergeRequestInterface;
use App\Domain\Gitlab\Source\GitlabSourceProjectInterface;
use App\Domain\Gitlab\Source\GitlabSourceResourceLabelEventInterface;
use App\Domain\Gitlab\Source\GitlabSourceUserInterface;
use App\Domain\Gitlab\Event\Repository\GitlabSourceEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabStorageEventRepositoryInterface;
use App\Domain\Gitlab\Label\Repository\GitlabSourceLabelRepositoryInterface;
use App\Domain\Gitlab\Label\Repository\GitlabStorageLabelRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabSourceMergeRequestRepositoryInterface;
use App\Domain\Gitlab\MergeRequest\Repository\GitlabStorageMergeRequestRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabSourceProjectRepositoryInterface;
use App\Domain\Gitlab\Project\Repository\GitlabStorageProjectRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabSourceResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\ResourceLabelEvent\Repository\GitlabStorageResourceLabelEventRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabSourceUserRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabStorageUserRepositoryInterface;
use App\Domain\Git\Commit\CommitFactory;
use App\Domain\Git\Stats\StatsFactory;
use App\Domain\Gitlab\Commit\CommitFactory as GitlabCommitFactory;
use App\Domain\Gitlab\CommitStats\CommitStatsFactory;
use App\Domain\Gitlab\Event\EventFactory;
use App\Domain\Gitlab\MergeRequest\MergeRequestFactory;
use App\Domain\Gitlab\Note\NoteFactory;
use App\Domain\Gitlab\Project\Factory\ProjectFactory;
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
    'GITLAB_URI' => function (ContainerInterface $c): string {
        return $c->get(GitlabConfiguration::class)->gitlabUrl . '/api/v4/';
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
            $c->get(GitlabSourceProjectRepositoryInterface::class),
            $c->get(GitlabStorageProjectRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(60),
        );
    },
    EventFilterCollection::class => function (): EventFilterCollection {
        $collection = new EventFilterCollection();
        $collection->add(new EventFilter(EventFilterParam::Action, EventFilterValue::Pushed));
        $collection->add(new EventFilter(EventFilterParam::Action, EventFilterValue::Commented));
        $collection->add(new EventFilter(EventFilterParam::TargetType, EventFilterValue::MergeRequest));

        return $collection;
    },
    SyncGitlabProjectEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectEventsUseCase(
            new SyncDateAfter($c->get(GitlabConfiguration::class)->syncDateAfter),
            $c->get(GitlabStorageProjectRepositoryInterface::class),
            $c->get(GitlabSourceEventRepositoryInterface::class),
            $c->get(GitlabStorageEventRepositoryInterface::class),
            $c->get(EventFilterCollection::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabProjectCommitsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectCommitsUseCase(
            new SyncDateAfter($c->get(GitlabConfiguration::class)->syncDateAfter),
            $c->get(GitlabStorageProjectRepositoryInterface::class),
            $c->get(GitlabSourceCommitRepositoryInterface::class),
            $c->get(GitlabStorageCommitRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabProjectCommitStatsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabProjectCommitStatsUseCase(
            new CommitSinceDate($c->get(GitlabConfiguration::class)->syncDateAfter),
            $c->get(GitRepositoryInterface::class),
            $c->get(GitlabStorageProjectRepositoryInterface::class),
            $c->get(GitlabStorageCommitStatsRepositoryInterface::class),
            $c->get(CommitStatsFactory::class),
            $c->get(SyncOutputInterface::class),
        );
    },
    SyncGitlabUsersUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabUsersUseCase(
            $c->get(GitlabSourceUserRepositoryInterface::class),
            $c->get(GitlabStorageUserRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabLabelsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabLabelsUseCase(
            $c->get(GitlabSourceLabelRepositoryInterface::class),
            $c->get(GitlabStorageLabelRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(60),
        );
    },
    SyncGitlabMergeRequestLabelEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabMergeRequestLabelEventsUseCase(
            new SyncDateAfter($c->get(GitlabConfiguration::class)->syncDateAfter),
            $c->get(GitlabSourceResourceLabelEventRepositoryInterface::class),
            $c->get(GitlabStorageResourceLabelEventRepositoryInterface::class),
            $c->get(GitlabStorageMergeRequestRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(60),
        );
    },
    'REPORT_TESTED_LABELS' => function (ContainerInterface $c): array {
        return array_map(
            static fn(string $name): LabelName => new LabelName($name),
            $c->get(GitlabConfiguration::class)->reportTestedLabels,
        );
    },
    ScoringConfiguration::class => function (ContainerInterface $c): ScoringConfiguration {
        $config = $c->get(GitlabConfiguration::class);
        return new ScoringConfiguration(
            mergeRequestCreated: new ScoringWeight($config->pointsMergeRequestCreated),
            approvalsGiven: new ScoringWeight($config->pointsApprovalsGiven),
            mergeRequestMerged: new ScoringWeight($config->pointsMergeRequestMerged),
            mergeRequestApproved: new ScoringWeight($config->pointsMergeRequestApproved),
            mergeRequestTested: new ScoringWeight($config->pointsMergeRequestTested),
            linesAdded: new ScoringWeight($config->pointsLinesAdded),
            linesRemoved: new ScoringWeight($config->pointsLinesRemoved),
            selfApprovals: new ScoringPenalty($config->pointsSelfApprovals),
            directCommitsToMain: new ScoringPenalty($config->pointsDirectCommitsToMain),
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
    'DevReportHtmlUseCase' => function (ContainerInterface $c): UseCaseInterface {
        return new DevReportUseCase(
            $c->get(DevReportRepositoryInterface::class),
            $c->get(ScoringService::class),
            new DevReportHtmlPresenter('/var/www/reports', new ConsoleOutput()),
            $c->get(ReportDateProviderInterface::class),
            $c->get('REPORT_TESTED_LABELS'),
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
            new SyncDateAfter($c->get(GitlabConfiguration::class)->syncDateAfter),
            $c->get(GitlabSourceMergeRequestRepositoryInterface::class),
            $c->get(GitlabStorageMergeRequestRepositoryInterface::class),
            $c->get(GitlabStorageProjectRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(40),
        );
    },
    SyncGitlabUserEventsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncGitlabUserEventsUseCase(
            new SyncDateAfter($c->get(GitlabConfiguration::class)->syncDateAfter),
            $c->get(GitlabStorageUserRepositoryInterface::class),
            $c->get(GitlabSourceEventRepositoryInterface::class),
            $c->get(GitlabStorageEventRepositoryInterface::class),
            $c->get(SyncOutputInterface::class),
            new Paginator(20),
        );
    },

    GitlabSourceProjectRepositoryInterface::class => function (ContainerInterface $c): GitlabSourceProjectRepositoryInterface {
        return new GitlabApiProjectRepository(
            $c->get(GitlabSourceProjectInterface::class),
            $c->get(ProjectFactory::class),
            $c->get(GitlabConfiguration::class)->excludedProjectIds,
        );
    },
    GitlabSourceUserRepositoryInterface::class => function (ContainerInterface $c): GitlabSourceUserRepositoryInterface {
        return new GitlabApiUserRepository(
            $c->get(GitlabSourceUserInterface::class),
            $c->get(GitlabConfiguration::class)->excludedUserIds,
        );
    },
    GitlabSourceLabelRepositoryInterface::class => function (ContainerInterface $c): GitlabSourceLabelRepositoryInterface {
        return new GitlabApiLabelRepository(
            $c->get(GitlabSourceLabelInterface::class),
        );
    },
    GitlabSourceResourceLabelEventRepositoryInterface::class => function (ContainerInterface $c): GitlabSourceResourceLabelEventRepositoryInterface {
        return new GitlabApiResourceLabelEventRepository(
            $c->get(GitlabSourceResourceLabelEventInterface::class),
        );
    },
    GitlabSourceMergeRequestRepositoryInterface::class => function (ContainerInterface $c): GitlabSourceMergeRequestRepositoryInterface {
        return new GitlabApiMergeRequestRepository(
            $c->get(GitlabSourceMergeRequestInterface::class),
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
    GitlabCommitFactory::class => function (): GitlabCommitFactory {
        return new GitlabCommitFactory();
    },
    MergeRequestFactory::class => function (): MergeRequestFactory {
        return new MergeRequestFactory();
    },
    ProjectFactory::class => function (): ProjectFactory {
        return new ProjectFactory();
    },
    GitlabSourceEventRepositoryInterface::class => function (ContainerInterface $c): GitlabSourceEventRepositoryInterface {
        return new GitlabApiEventRepository(
            $c->get(GitlabSourceEventInterface::class),
            $c->get(EventFactory::class),
        );
    },
    GitRepositoryInterface::class => function (ContainerInterface $c): GitRepositoryInterface {
        return new GitRepository(
            $c->get(GitlabConfiguration::class)->gitLogExcludePath,
            $c->get(CommitFactory::class),
        );
    },
    GitlabSourceCommitRepositoryInterface::class => function (ContainerInterface $c): GitlabSourceCommitRepositoryInterface {
        return new GitlabApiCommitRepository(
            $c->get(GitlabSourceCommitInterface::class),
            $c->get(GitlabCommitFactory::class),
        );
    },
    GitlabApiClient::class => function (ContainerInterface $c): GitlabApiClient {
        $config = $c->get(GitlabConfiguration::class);
        return new GitlabApiClient(
            $c->get('GITLAB_URI'),
            $config->gitlabToken,
            $config->gitlabGroupId,
        );
    },
    GitlabSourceProjectInterface::class => function (ContainerInterface $c): GitlabSourceProjectInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabSourceUserInterface::class => function (ContainerInterface $c): GitlabSourceUserInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabSourceMergeRequestInterface::class => function (ContainerInterface $c): GitlabSourceMergeRequestInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabSourceEventInterface::class => function (ContainerInterface $c): GitlabSourceEventInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabSourceCommitInterface::class => function (ContainerInterface $c): GitlabSourceCommitInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabSourceLabelInterface::class => function (ContainerInterface $c): GitlabSourceLabelInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabSourceResourceLabelEventInterface::class => function (ContainerInterface $c): GitlabSourceResourceLabelEventInterface {
        return $c->get(GitlabApiClient::class);
    },
    GitlabStorageProjectRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageProjectRepositoryInterface {
        return new GitlabMySqlProjectRepository(
            $c->get(PDO::class),
            $c->get(ProjectFactory::class),
        );
    },
    GitlabStorageUserRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageUserRepositoryInterface {
        return new GitlabMySqlUserRepository(
            $c->get(PDO::class),
        );
    },
    GitlabStorageLabelRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageLabelRepositoryInterface {
        return new GitlabMySqlLabelRepository(
            $c->get(PDO::class),
        );
    },
    GitlabStorageResourceLabelEventRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageResourceLabelEventRepositoryInterface {
        return new GitlabMySqlResourceLabelEventRepository(
            $c->get(PDO::class),
        );
    },
    GitlabStorageMergeRequestRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageMergeRequestRepositoryInterface {
        return new GitlabMySqlMergeRequestRepository(
            $c->get(PDO::class),
            $c->get(MergeRequestFactory::class),
        );
    },
    GitlabStorageEventRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageEventRepositoryInterface {
        return new GitlabMySqlEventRepository(
            $c->get(PDO::class),
        );
    },
    GitlabStorageCommitRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageCommitRepositoryInterface {
        return new GitlabMySqlCommitRepository(
            $c->get(PDO::class),
        );
    },
    GitlabStorageCommitStatsRepositoryInterface::class => function (ContainerInterface $c): GitlabStorageCommitStatsRepositoryInterface {
        return new GitlabMySqlCommitStatsRepository(
            $c->get(PDO::class),
        );
    },
];
