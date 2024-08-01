<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabApiMergeRequestRepositoryInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabDataBaseMergeRequestRepositoryInterface;
use App\Domain\GitLab\Project\Repository\GitLabDataBaseProjectRepositoryInterface;

final class SyncGitLabProjectMergeRequestsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;
    private const COUNT_PAGES = 5;

    private GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository;
    private GitLabDataBaseMergeRequestRepositoryInterface $gitLabDataBaseMergeRequestRepository;
    private GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository;

    public function __construct(
        GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository,
        GitLabDataBaseMergeRequestRepositoryInterface $gitLabDataBaseMergeRequestRepository,
        GitLabDataBaseProjectRepositoryInterface $gitLabDataBaseProjectRepository
    ) {
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
                if ($page > self::COUNT_PAGES) {
                    break;
                }

                $mergeRequestCollection = $this->gitLabApiMergeRequestRepository->get($projectId, [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
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
