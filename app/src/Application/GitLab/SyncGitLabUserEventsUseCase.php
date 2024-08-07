<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Event\Repository\GitLabApiEventRepositoryInterface;
use App\Domain\GitLab\Event\Repository\GitLabDataBaseEventRepositoryInterface;
use App\Domain\GitLab\User\Repository\GitLabDataBaseUserRepositoryInterface;

final readonly class SyncGitLabUserEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    public function __construct(
        private string $syncDateAfter,
        private GitLabApiEventRepositoryInterface $gitLabApiEventRepository,
        private GitLabDataBaseEventRepositoryInterface $gitLabDataBaseEventRepository,
        private GitLabDataBaseUserRepositoryInterface $gitLabDataBaseUserRepository,
    ) {
    }

    public function execute(): void
    {
        $userCollection = $this->gitLabDataBaseUserRepository->getAll();
        foreach ($userCollection as $user) {
            $page = 0;
            $userId = $user->getId()->getValue();
            $userName = $user->getName()->getValue();
            echo 'Load events for #' . $userId . ' ' . $userName;
            do {
                ++$page;

                $eventCollection = $this->gitLabApiEventRepository->getByUserId($userId, [
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
