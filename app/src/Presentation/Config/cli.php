<?php

declare(strict_types=1);

use App\Application\Cli\ExitUseCase;
use App\Application\Cli\MenuUseCase;
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
use App\Application\UseCaseInterface;
use App\Presentation\Cli\Command;
use Psr\Container\ContainerInterface;

return [
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
    Command::sync_gitlab_project_commit_stats->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabProjectCommitStatsUseCase::class);
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
    Command::sync_gitlab_labels->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabLabelsUseCase::class);
    },
    Command::sync_gitlab_merge_request_label_events->diId() => function (ContainerInterface $c) {
        return $c->get(SyncGitlabMergeRequestLabelEventsUseCase::class);
    },
    Command::dev_report->diId() => function (ContainerInterface $c) {
        return $c->get(DevReportUseCase::class);
    },

    MenuUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new MenuUseCase();
    },
    ExitUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new ExitUseCase();
    },
];
