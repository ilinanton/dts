<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
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
        private SyncOutputInterface $output,
    ) {
    }

    public function execute(): void
    {
        $userCollection = $this->dataBaseUserRepository->getAll();
        $this->output->writeLine('Load events that happened after ' . $this->syncDateAfter);
        foreach ($userCollection as $user) {
            $page = 0;
            $userId = $user->id->value;
            $userName = $user->name->value;
            $this->output->write(' - #' . $userId . ' ' . $userName);
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
                $this->output->write(' .');
            } while (self::COUNT_ITEMS_PER_PAGE === count($eventCollection));
            $this->output->writeLine(' done');
        }
    }
}
