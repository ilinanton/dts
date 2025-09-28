<?php

declare(strict_types=1);

namespace App\Infrastructure\Git;

use App\Domain\Git\Commit\CommitCollection;
use App\Domain\Git\Commit\CommitFactory;
use App\Domain\Git\Common\GitRepositoryInterface;
use App\Domain\Git\Project\Project;
use App\Domain\Git\Project\ProjectCollection;
use App\Domain\Git\Project\ProjectFactory;

final readonly class GitRepository implements GitRepositoryInterface
{
    private const string PROJECTS_PATH = '../projects';

    public function __construct(
        private array $logExcludePath,
    ) {
    }

    public function getProjects(): ProjectCollection
    {
        $result = trim(shell_exec('cd ' . self::PROJECTS_PATH . ' && ls -d */'));
        $directories = explode(PHP_EOL, $result);
        $collection = new ProjectCollection();
        $factory = new ProjectFactory();

        foreach ($directories as $directory) {
            $path = self::PROJECTS_PATH . DS . $directory;
            $name = rtrim($directory, DS);
            $branch = ltrim(trim(shell_exec(sprintf("cd %s && git branch | grep '*'", $path))), '* ');
            $url = trim(shell_exec(sprintf('cd %s && git config --get remote.origin.url', $path)));

            $collection->add($factory->create([
                'name' => $name,
                'branch' => $branch,
                'url' => $url,
                'path' => $path,
            ]));
        }

        return $collection;
    }

    public function getCommits(Project $project, string $since): CommitCollection
    {
        $collection = new CommitCollection();
        $factory = new CommitFactory();

        $exclude = '';
        if ($this->logExcludePath !== []) {
            $exclude = " -- ':(exclude)" .
                implode("' ':(exclude)", $this->logExcludePath) .
                "'";
        }

        $log = shell_exec(sprintf('cd %s ', $project->path->value) .
            sprintf("&& git log --shortstat --no-merges --date=iso-local --since='%s'", $since) .
            " --format='{|c|}{|p|}commit: %H{|p|}date: %aI{|p|}name: %aN{|p|}email: %aE{|p|}stat:'" .
            ($exclude . ' | tr \'
\' \' \'')) ?? '';
        $logItems = explode('{|c|}', $log);

        foreach ($logItems as $logItem) {
            if ($logItem === '') {
                continue;
            }
            $collection->add($factory->create($logItem));
        }

        return $collection;
    }
}
