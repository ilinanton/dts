<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;

final class SyncGitLabEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiEventRepositoryInterface $gitLabApiEventRepository;

    public function __construct(
        GitLabApiEventRepositoryInterface $gitLabApiEventRepository,
    ) {
        $this->gitLabApiEventRepository = $gitLabApiEventRepository;
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $eventCollection = $this->gitLabApiEventRepository->getByProjectId(17801372, $page, self::COUNT_ITEMS_PER_PAGE);
            foreach ($eventCollection as $event) {
                var_dump($event);
                sleep(2);
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
    }
}
