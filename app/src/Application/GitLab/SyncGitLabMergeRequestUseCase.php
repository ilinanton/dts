<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\MergeRequest\Repository\GitLabApiMergeRequestRepositoryInterface;

final class SyncGitLabMergeRequestUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository;

    public function __construct(
        GitLabApiMergeRequestRepositoryInterface $gitLabApiMergeRequestRepository,
    ) {
        $this->gitLabApiMergeRequestRepository = $gitLabApiMergeRequestRepository;
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $mergeRequestCollection = $this->gitLabApiMergeRequestRepository->get($page, self::COUNT_ITEMS_PER_PAGE);
            foreach ($mergeRequestCollection as $mergeRequest) {
                var_dump($mergeRequest);
                sleep(5);
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($mergeRequestCollection));
    }
}
