<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\User\Repository\GitLabApiUserRepositoryInterface;
use App\Infrastructure\GitLab\GitLabMySqlUserRepository;

final readonly class SyncGitLabUsersUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    public function __construct(
        private GitLabApiUserRepositoryInterface $gitLabApiUserRepository,
        private GitLabMySqlUserRepository $gitLabMySqlUserRepository
    ) {
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $userCollection = $this->gitLabApiUserRepository->get([
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($userCollection as $user) {
                echo 'Load user #' . $user->getId()->getValue() . ' ' . $user->getName()->getValue();
                $this->gitLabMySqlUserRepository->save($user);
                echo ' done ' . PHP_EOL;
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($userCollection));
    }
}
