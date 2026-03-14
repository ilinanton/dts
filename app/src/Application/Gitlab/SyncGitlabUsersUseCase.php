<?php

declare(strict_types=1);

namespace App\Application\Gitlab;

use App\Application\Common\Paginator;
use App\Application\SyncOutputInterface;
use App\Application\UseCaseInterface;
use App\Domain\Gitlab\User\Repository\GitlabApiUserRepositoryInterface;
use App\Domain\Gitlab\User\Repository\GitlabDataBaseUserRepositoryInterface;
use App\Domain\Gitlab\User\UserCollection;

final readonly class SyncGitlabUsersUseCase implements UseCaseInterface
{
    public function __construct(
        private GitlabApiUserRepositoryInterface $apiUserRepository,
        private GitlabDataBaseUserRepositoryInterface $dataBaseUserRepository,
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
