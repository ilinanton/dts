<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;
use App\Domain\GitLab\Event\Repository\GitLabDataBaseEventRepositoryInterface;

final class SyncGitLabEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiEventRepositoryInterface $gitLabApiEventRepository;
    private GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository;

    public function __construct(
        GitLabApiEventRepositoryInterface $gitLabApiEventRepository,
        GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository,
    ) {
        $this->gitLabApiEventRepository = $gitLabApiEventRepository;
        $this->gitLabDataBaseEventRepository = $gitLabDataBaseEventRepository;
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $eventCollection = $this->gitLabApiEventRepository->getByProjectId(17801372, [
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($eventCollection as $event) {
                $this->gitLabDataBaseEventRepository->save($event);
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
    }
}
