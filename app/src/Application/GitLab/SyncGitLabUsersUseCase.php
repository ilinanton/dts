<?php

namespace App\Application\GitLab;

use App\Application\UseCaseInterface;
use App\Domain\GitLab\Member\Repository\GitLabApiMemberRepositoryInterface;
use App\Infrastructure\GitLab\GitLabMySqlMemberRepository;

final class SyncGitLabUsersUseCase implements UseCaseInterface
{
    private const COUNT_ITEMS_PER_PAGE = 20;

    private GitLabApiMemberRepositoryInterface $gitLabApiMemberRepository;
    private GitLabMySqlMemberRepository $gitLabMySqlMemberRepository;


    public function __construct(
        GitLabApiMemberRepositoryInterface $gitLabApiMemberRepository,
        GitLabMySqlMemberRepository $gitLabMySqlMemberRepository
    ) {
        $this->gitLabApiMemberRepository = $gitLabApiMemberRepository;
        $this->gitLabMySqlMemberRepository = $gitLabMySqlMemberRepository;
    }

    public function execute(): void
    {
        $page = 0;
        do {
            ++$page;
            $projectCollection = $this->gitLabApiMemberRepository->get([
                'page' => $page,
                'per_page' => self::COUNT_ITEMS_PER_PAGE,
            ]);
            foreach ($projectCollection as $project) {
                $this->gitLabMySqlMemberRepository->save($project);
            }
        } while (self::COUNT_ITEMS_PER_PAGE === count($projectCollection));
    }
}
