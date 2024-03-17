<?php

namespace App\Application;

enum Command: string
{
    case menu = 'Menu';
    case exit = 'Exit';
    case sync_gitlab_projects = 'Sync gitlab projects';
    case sync_gitlab_users = 'Sync gitlab users';

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
        $matchingCommandIndex = array_search($this->name, array_column($list, "name"));
        if (false === $matchingCommandIndex) {
            throw new \Exception('Command not found!');
        }
        return $matchingCommandIndex;
    }
}
