<?php

declare(strict_types=1);

namespace App\Presentation\Cli;

enum Command: string
{
    public const string CATEGORY_GENERAL = 'General';
    public const string CATEGORY_GITLAB = 'GitLab';
    public const string CATEGORY_WEEEK = 'Weeek';
    public const string CATEGORY_OTHER = 'Other';
    public const array CATEGORY = [
        self::CATEGORY_GENERAL,
        self::CATEGORY_GITLAB,
        self::CATEGORY_WEEEK,
    ];

    case exit = 'Exit';
    case menu = 'Menu';
    case dev_report = 'Dev report';
    case sync_gitlab_data = 'Sync users, projects and labels';
    case sync_gitlab_merge_requests = 'Sync project merge requests';
    case sync_gitlab_project_events = 'Sync project events';
    case sync_gitlab_merge_request_label_events = 'Sync merge request label events';
    case sync_gitlab_project_commits = 'Sync project commits';
    case sync_gitlab_project_commit_stats = 'Sync project commit stats';
    case sync_gitlab_user_events = 'Sync user events';
    case sync_gitlab_users = 'Sync users';
    case sync_gitlab_projects = 'Sync projects';
    case sync_gitlab_labels = 'Sync labels';
    case sync_weeek_users = 'Sync ws users';

    public function diId(): string
    {
        return 'command_' . $this->name;
    }

    public static function getByIndex(int $index): self
    {
        $list = Command::cases();
        if (array_key_exists($index, $list)) {
            return $list[$index];
        }

        throw new \Exception('Command not found!');
    }

    public function id(): int
    {
        $list = Command::cases();
        $matchingCommandIndex = array_search($this->name, array_column($list, 'name'), true);
        if (false === $matchingCommandIndex) {
            throw new \Exception('Command not found!');
        }
        return $matchingCommandIndex;
    }

    public function category(): string
    {
        return match ($this) {
            self::exit,
            self::dev_report => self::CATEGORY_GENERAL,

            self::sync_gitlab_data,
            self::sync_gitlab_merge_requests,
            self::sync_gitlab_project_events,
            self::sync_gitlab_project_commits,
            self::sync_gitlab_project_commit_stats,
            self::sync_gitlab_user_events,
            self::sync_gitlab_merge_request_label_events => self::CATEGORY_GITLAB,

            self::sync_weeek_users => self::CATEGORY_WEEEK,

            default => self::CATEGORY_OTHER,
        };
    }
}
