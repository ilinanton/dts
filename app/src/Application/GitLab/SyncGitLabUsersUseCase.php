<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\User\Repository\GitLabApiUserRepositoryInterface;
use App\Infrastructure\GitLab\GitLabMySqlUserRepository;

final class SyncGitLabUsersUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiUserRepositoryInterface $gitLabApiUserRepository;
    private GitLabMySqlUserRepository $gitLabMySqlUserRepository;


    public function __construct(
        GitLabApiUserRepositoryInterface $gitLabApiUserRepository,
        GitLabMySqlUserRepository $gitLabMySqlUserRepository
    ) {
        $this->gitLabApiUserRepository = $gitLabApiUserRepository;
        $this->gitLabMySqlUserRepository = $gitLabMySqlUserRepository;
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
                $this->gitLabMySqlUserRepository->save($user);
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($userCollection));
    }
}
