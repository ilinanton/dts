<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;
use App\Domain\GitLab\Event\Repository\GitLabDataBaseEventRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;

final class SyncGitLabProjectEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private string $syncDateAfter;
    private GitLabApiEventRepositoryInterface $gitLabApiEventRepository;
    private GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository;
    private GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository;

    public function __construct(
        string $syncDateAfter,
        GitLabApiEventRepositoryInterface $gitLabApiEventRepository,
        GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository,
        GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository
    ) {
        $this->syncDateAfter = $syncDateAfter;
        $this->gitLabApiEventRepository = $gitLabApiEventRepository;
        $this->gitLabDataBaseEventRepository = $gitLabDataBaseEventRepository;
        $this->gitLabDataBaseProjectRepository = $gitLabDataBaseProjectRepository;
    }

    public function execute(): void
    {
        $projectCollection = $this->gitLabDataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $page = 0;
            $projectId = $project->getId()->getValue();
            $projectName = $project->getName()->getValue();
            echo 'Load events for #' . $projectId . ' ' . $projectName;
            do {
                ++$page;

                $eventCollection = $this->gitLabApiEventRepository->getByProjectId($projectId, [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'after' => $this->syncDateAfter,
                    'target_type' => 'merge_request',
                ]);

                foreach ($eventCollection as $event) {
                    $this->gitLabDataBaseEventRepository->save($event);
                }
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
            echo ' done ' . PHP_EOL;
        }
    }
}
