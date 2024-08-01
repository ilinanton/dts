<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;
use App\Domain\GitLab\Event\Repository\GitLabDataBaseEventRepositoryInterface;
use App\Domain\GitLab\Member\Repository\GitLabDataBaseMemberRepositoryInterface;

final class SyncGitLabUserEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiEventRepositoryInterface $gitLabApiEventRepository;
    private GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository;
    private GitLabDataBaseMemberRepositoryInterface $gitLabDataBaseMemberRepository;

    public function __construct(
        GitLabApiEventRepositoryInterface $gitLabApiEventRepository,
        GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository,
        GitLabDataBaseMemberRepositoryInterface $gitLabDataBaseMemberRepository
    ) {
        $this->gitLabApiEventRepository = $gitLabApiEventRepository;
        $this->gitLabDataBaseEventRepository = $gitLabDataBaseEventRepository;
        $this->gitLabDataBaseMemberRepository = $gitLabDataBaseMemberRepository;
    }

    public function execute(): void
    {
        $memberCollection = $this->gitLabDataBaseMemberRepository->getAll();
        foreach ($memberCollection as $member) {
            $page = 0;
            $userId = $member->getId()->getValue();
            $name = $member->getName()->getValue();
            echo 'Load events for ' . $name . ' (' . $userId . ')';
            do {
                ++$page;
                $eventCollection = $this->gitLabApiEventRepository->getByUserId($userId, [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                ]);

                foreach ($eventCollection as $event) {
                    $this->gitLabDataBaseEventRepository->save($event);
                }
                sleep(1);
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection) && $page <= 4);
            echo ' done ' . PHP_EOL;
        }
    }
}
