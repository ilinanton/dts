<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;

final readonly class SyncGitlabUserEventsUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    public function __construct(
        private string $syncDateAfter,
        private GitlabDataBaseUserRepositoryInterface $gitlabDataBaseUserRepository,
        private GitlabApiEventRepositoryInterface $gitlabApiEventRepository,
        private GitlabDataBaseEventRepositoryInterface $gitlabDataBaseEventRepository,
    ) {
    }

    public function execute(): void
    {
        $userCollection = $this->gitlabDataBaseUserRepository->getAll();
        echo 'Load events that happened after ' . $this->syncDateAfter . PHP_EOL;
        foreach ($userCollection as $user) {
            $page = 0;
            $userId = $user->getId()->getValue();
            $userName = $user->getName()->getValue();
            echo ' - #' . $userId . ' ' . $userName;
            do {
                ++$page;

                $eventCollection = $this->gitlabApiEventRepository->getByUserId($userId, [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'after' => $this->syncDateAfter,
                    'target_type' => 'merge_request',
                ]);

                foreach ($eventCollection as $event) {
                    $this->gitlabDataBaseEventRepository->save($event);
                }
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
            echo ' done ' . PHP_EOL;
        }
    }
}
