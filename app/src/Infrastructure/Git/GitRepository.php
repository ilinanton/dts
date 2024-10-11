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
    private const PROJECTS_PATH = '../projects';

    public function __construct(
        private ProjectFactory $projectFactory,
        private CommitFactory $commitFactory,
    ) {
    }

    public function getProjects(): ProjectCollection
    {
        $result = trim(shell_exec("cd " . self::PROJECTS_PATH . " && ls -d */"));
        $directories = explode(PHP_EOL, $result);
        $projectCollection = new ProjectCollection();

        foreach ($directories as $directory) {
            $path = self::PROJECTS_PATH . DS . $directory;
            $name = rtrim($directory, DS);
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
        $commitCollection = new CommitCollection();

        $log = shell_exec("cd {$project->path->getValue()} " .
            "&& git log --shortstat --no-merges --date=iso-local --since='{$since}'" .
            " --format='{|c|}{|p|}commit: %H{|p|}date: %aI{|p|}name: %aN{|p|}email: %aE{|p|}stat:'" .
            " -- ':(exclude)composer.lock' ':(exclude)package-lock.json'" .
            " | tr '\n' ' '") ?? '';
        $logItems = explode('{|c|}', $log);

        foreach ($logItems as $logItem) {
            if (0 === strlen($logItem)) {
                continue;
            }
            $commitCollection->add($this->commitFactory->create($logItem));
        }

        return $commitCollection;
    }
}
