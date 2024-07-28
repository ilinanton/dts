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
                sleep(5);
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($mergeRequestCollection));


////        $uri = 'projects/17801372/merge_requests/7354/approve';
//        $uri = 'projects/17801372/events?' . http_build_query([
//                'page' => $page,
//                'per_page' => $perPage,
//            ]);
//        $response = $this->client->get($uri);
//        $body = (string)$response->getBody();
//        var_dump($body);
    }
}
