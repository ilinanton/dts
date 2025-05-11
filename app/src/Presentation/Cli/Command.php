<?php

declare(strict_types=1);

namespace App\Presentation\Cli;

enum Command: string
{
    case exit = 'Exit';
    case menu = 'Menu';
    case sync_gitlab_data = 'Sync gitlab data';
    case sync_gitlab_projects = 'Sync gitlab projects';
    case sync_gitlab_merge_requests = 'Sync gitlab project merge requests';
    case sync_gitlab_project_events = 'Sync gitlab project events';
    case sync_gitlab_project_commits = 'Sync gitlab project commits';
    case sync_gitlab_project_commit_stats = 'Sync gitlab project commit stats';
    case sync_gitlab_users = 'Sync gitlab users';
    case sync_gitlab_user_events = 'Sync gitlab user events';
    case sync_gitlab_labels = 'Sync gitlab labels';
    case sync_gitlab_label_events = 'Sync gitlab label events';

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
        $matchingCommandIndex = array_search($this->name, array_column($list, 'name'));
        if (false === $matchingCommandIndex) {
            throw new \Exception('Command not found!');
        }
        return $matchingCommandIndex;
    }
}
