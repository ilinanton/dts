<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;

final readonly class SyncGitlabUserEventsUseCase implements UseCaseInterface
{
    private const int COUNT_ITEMS_PER_PAGE = 20;

    public function __construct(
        private string $syncDateAfter,
        private GitlabDataBaseUserRepositoryInterface $dataBaseUserRepository,
        private GitlabApiEventRepositoryInterface $apiEventRepository,
        private GitlabDataBaseEventRepositoryInterface $dataBaseEventRepository,
    ) {
    }

    public function execute(): void
    {
        $userCollection = $this->dataBaseUserRepository->getAll();
        echo 'Load events that happened after ' . $this->syncDateAfter . PHP_EOL;
        foreach ($userCollection as $user) {
            $page = 0;
            $userId = $user->id->value;
            $userName = $user->name->value;
            echo ' - #' . $userId . ' ' . $userName;
            do {
                ++$page;

                $eventCollection = $this->apiEventRepository->getByUserId($userId, [
                    'page' => $page,
                    'per_page' => self::COUNT_ITEMS_PER_PAGE,
                    'after' => $this->syncDateAfter,
                    'target_type' => 'merge_request',
                ]);

                foreach ($eventCollection as $event) {
                    $this->dataBaseEventRepository->save($event);
                }
                echo ' .';
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
            echo ' done ' . PHP_EOL;
        }
    }
}
