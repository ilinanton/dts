<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabApiMergeRequestRepositoryInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabDataBaseMergeRequestRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;

final class SyncGitLabProjectMergeRequestsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private string $syncDateAfter;
    private GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository;
    private GitLabDataBaseMergeRequestRepositoryInterface $gitLabDataBaseMergeRequestRepository;
    private GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository;

    public function __construct(
        string $syncDateAfter,
        GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository,
        GitLabDataBaseMergeRequestRepositoryInterface $gitLabDataBaseMergeRequestRepository,
        GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository
    ) {
        $this->syncDateAfter = $syncDateAfter;
        $this->gitLabApiMergeRequestRepository = $gitLabApiMergeRequestRepository;
        $this->gitLabDataBaseMergeRequestRepository = $gitLabDataBaseMergeRequestRepository;
        $this->gitLabDataBaseProjectRepository = $gitLabDataBaseProjectRepository;
    }

    public function execute(): void
    {
        $projectCollection = $this->gitLabDataBaseProjectRepository->getAll();
        foreach ($projectCollection as $project) {
            $page = 0;
            $projectId = $project->getId()->getValue();
            $projectName = $project->getName()->getValue();
            echo 'Load merge requests for #' . $projectId . ' ' . $projectName;
            do {
                ++$page;

                $mergeRequestCollection = $this->gitLabApiMergeRequestRepository->get($projectId, [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'created_after' => $this->syncDateAfter,
                ]);

                foreach ($mergeRequestCollection as $mergeRequest) {
                    $this->gitLabDataBaseMergeRequestRepository->save($mergeRequest);
                }

                sleep(1);
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($mergeRequestCollection));
            echo ' done ' . PHP_EOL;
        }
    }
}
