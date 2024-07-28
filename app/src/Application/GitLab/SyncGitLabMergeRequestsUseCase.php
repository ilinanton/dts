<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabApiMergeRequestRepositoryInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabDataBaseMergeRequestRepositoryInterface;

final class SyncGitLabMergeRequestsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository;
    private GitLabDataBaseMergeRequestRepositoryInterface $gitLabDataBaseMergeRequestRepository;

    public function __construct(
        GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository,
        GitLabDataBaseMergeRequestRepositoryInterface $gitLabDataBaseMergeRequestRepository,
    ) {
        $this->gitLabApiMergeRequestRepository = $gitLabApiMergeRequestRepository;
        $this->gitLabDataBaseMergeRequestRepository = $gitLabDataBaseMergeRequestRepository;
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $mergeRequestCollection = $this->gitLabApiMergeRequestRepository->get($page, self::COUNT_ITEMS_PER_PAGE);
            foreach ($mergeRequestCollection as $mergeRequest) {
                $this->gitLabDataBaseMergeRequestRepository->save($mergeRequest);
            }
            sleep(2);
        } while (self::COUNT_ITEMS_PER_PAGE === count($mergeRequestCollection));
    }
}
