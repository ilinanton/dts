<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\User\Repository\GitlabApiUserRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;

final readonly class SyncGitlabUsersUseCase implements UseCaseInterface
{
    private const int COUNT_ITEMS_PER_PAGE = 40;

    public function __construct(
        private GitlabApiUserRepositoryInterface $apiUserRepository,
        private GitlabDataBaseUserRepositoryInterface $dataBaseUserRepository,
        private SyncOutputInterface $output,
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $userCollection = $this->apiUserRepository->get([
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($userCollection as $user) {
                $this->dataBaseUserRepository->save($user);
                $this->output->writeLine('Load user #' . $user->id->value . ' ' . $user->name->value . ' done');
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($userCollection));
    }
}
