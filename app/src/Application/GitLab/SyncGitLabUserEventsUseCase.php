<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;
use App\Domain\GitLab\Event\Repository\GitLabDataBaseEventRepositoryInterface;
use App\Domain\GitLab\User\Repository\GitLabDataBaseUserRepositoryInterface;

final class SyncGitLabUserEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiEventRepositoryInterface $gitLabApiEventRepository;
    private GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository;
    private GitLabDataBaseUserRepositoryInterface $gitLabDataBaseUserRepository;

    public function __construct(
        GitLabApiEventRepositoryInterface $gitLabApiEventRepository,
        GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository,
        GitLabDataBaseUserRepositoryInterface $gitLabDataBaseUserRepository
    ) {
        $this->gitLabApiEventRepository = $gitLabApiEventRepository;
        $this->gitLabDataBaseEventRepository = $gitLabDataBaseEventRepository;
        $this->gitLabDataBaseUserRepository = $gitLabDataBaseUserRepository;
    }

    public function execute(): void
    {
        $userCollection = $this->gitLabDataBaseUserRepository->getAll();
        foreach ($userCollection as $user) {
            $page = 0;
            $userId = $user->getId()->getValue();
            $name = $user->getName()->getValue();
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
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection) && $page <= 4);
            echo ' done ' . PHP_EOL;
        }
    }
}
