<?php

namespace App\Application\Git;

use App\Application\UseCaseInterface;

final readonly class SyncGitDataUseCase implements UseCaseInterface
{

    public function __construct(
        private string $syncDateAfter,
    )
    {
    }

    public function execute(): void
    {
        $result = shell_exec("cd ./projects && ls -d */");
        $projects = explode(PHP_EOL, $result);
        foreach ($projects as $item) {
            if (0 === strlen($item)) {
                continue;
            }

            $dir = "./projects/{$item}";

            $result = shell_exec("cd {$dir} && git log --format='%aE|%cE|%aN|%cN' --since='{$this->syncDateAfter}' | sort -u ");
            $users = explode(PHP_EOL, $result);
            var_dump($users);
        }
    }
}
