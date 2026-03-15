<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\User\Repository\GitlabSourceUserRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabStorageUserRepositoryInterface;
use App\Domain\Gitlab\User\UserCollection;

final readonly class SyncGitlabUsersUseCase implements UseCaseInterface
{
    public function __construct(
        private GitlabSourceUserRepositoryInterface $apiUserRepository,
        private GitlabStorageUserRepositoryInterface $dataBaseUserRepository,
        private SyncOutputInterface $output,
        private Paginator $paginator,
    ) {
    }

    public function execute(): void
    {
        $items = $this->paginator->paginate(
            fn(array $params): UserCollection => $this->apiUserRepository->get($params),
        );
        foreach ($items as $user) {
            $this->dataBaseUserRepository->save($user);
            $this->output->writeLine('Load user #' . $user->id->value . ' ' . $user->name->value . ' done');
        }
    }
}
