<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseInterface;
use App\Domain\Gitlab\User\Repository\GitlabApiUserRepositoryInterface;
use App\Infrastructure\Gitlab\GitlabMySqlUserRepository;

final readonly class SyncGitlabUsersUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 40;

    public function __construct(
        private GitlabApiUserRepositoryInterface $gitlabApiUserRepository,
        private GitlabMySqlUserRepository $gitlabMySqlUserRepository,
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $userCollection = $this->gitlabApiUserRepository->get([
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($userCollection as $user) {
                echo 'Load user #' . $user->id->getValue() . ' ' . $user->name->getValue();
                $this->gitlabMySqlUserRepository->save($user);
                echo ' done ' . PHP_EOL;
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($userCollection));
    }
}
