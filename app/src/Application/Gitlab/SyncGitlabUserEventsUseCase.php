<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\Common\SyncDateAfter;
use App\Domain\Gitlab\Event\EventCollection;
use App\Domain\Gitlab\Event\Repository\GitlabApiEventRepositoryInterface;
use App\Domain\Gitlab\Event\Repository\GitlabDataBaseEventRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;

final readonly class SyncGitlabUserEventsUseCase implements UseCaseInterface
{
    public function __construct(
        private SyncDateAfter $syncDateAfter,
        private GitlabDataBaseUserRepositoryInterface $dataBaseUserRepository,
        private GitlabApiEventRepositoryInterface $apiEventRepository,
        private GitlabDataBaseEventRepositoryInterface $dataBaseEventRepository,
        private SyncOutputInterface $output,
        private Paginator $paginator,
    ) {
    }

    public function execute(): void
    {
        $userCollection = $this->dataBaseUserRepository->getAll();
        $this->output->writeLine('Load events that happened after ' . $this->syncDateAfter->getValueInMainFormat());
        foreach ($userCollection as $user) {
            $userId = $user->id->value;
            $userName = $user->name->value;
            $this->output->write(' - #' . $userId . ' ' . $userName);

            $baseParams = [
                'after' => $this->syncDateAfter->getValueInMainFormat(),
                'target_type' => 'merge_request',
            ];

            $items = $this->paginator->paginate(
                function (array $params) use ($userId): EventCollection {
                    $this->output->write(' .');
                    return $this->apiEventRepository->getByUserId($userId, $params);
                },
                $baseParams,
            );

            foreach ($items as $event) {
                $this->dataBaseEventRepository->save($event);
            }
            $this->output->writeLine(' done');
        }
    }
}
