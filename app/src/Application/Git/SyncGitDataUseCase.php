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
        foreach ($projects as $project) {
            if (0 === strlen($project)) {
                continue;
            }

            $dir = "./projects/{$project}";

            $result = shell_exec("cd {$dir} && git log --format='%aE|%cE|%aN|%cN' --since='{$this->syncDateAfter}' | sort -u ");
            $users = explode(PHP_EOL, $result);
            var_dump($users);

            $result = shell_exec("cd {$dir} && git log --shortstat --no-merges --format='{|c|}commit: %H{|p|}date: %aD{|p|}email: %aE{|p|}stat:' --since='{$this->syncDateAfter}' | tr '\n' ' '");
            $commits = explode('{|c|}', $result);
            foreach ($commits as $commit) {
                $commit = explode('{|p|}', $commit);
                var_dump($commit);
            }
        }
    }
}
