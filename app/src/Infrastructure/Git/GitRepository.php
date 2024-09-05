<?php

namespace App\Infrastructure\Git;

use App\Domain\Git\Commit\CommitCollection;
use App\Domain\Git\Commit\CommitFactory;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Git\Project\Project;
use App\Domain\Git\Project\ProjectCollection;
use App\Domain\Git\Project\ProjectFactory;

final readonly class GitRepository implements GitRepositoryInterface
{
    public function __construct(
        private ProjectFactory $projectFactory,
        private CommitFactory $commitFactory,
    ) {
    }

    public function getProjects(): ProjectCollection
    {
        $result = trim(shell_exec("cd ./projects && ls -d */"));
        $directories = explode(PHP_EOL, $result);
        $projectCollection = new ProjectCollection();

        foreach ($directories as $directory) {
            $path = "./projects/{$directory}";
            $name = rtrim($directory, '/');
            $branch = ltrim(trim(shell_exec("cd {$path} && git branch | grep '*'")), '* ');
            $url = trim(shell_exec("cd {$path} && git config --get remote.origin.url"));

            $projectCollection->add($this->projectFactory->create([
                'name' => $name,
                'branch' => $branch,
                'url' => $url,
                'path' => $path,
            ]));
        }

        return $projectCollection;
    }

    public function getCommits(Project $project, string $since): CommitCollection
    {
        $log = shell_exec("cd {$project->path->getValue()} " .
            "&& git log --shortstat --no-merges --date=iso-local" .
            " --format='{|c|}{|p|}commit: %H{|p|}date: %aI{|p|}email: %aE{|p|}stat:' --since='{$since}' | tr '\n' ' '");
        $logItems = explode('{|c|}', $log);
        foreach ($logItems as $logItem) {
            $this->commitFactory->create($logItem);
            var_dump($logItem);
        }
    }
}
