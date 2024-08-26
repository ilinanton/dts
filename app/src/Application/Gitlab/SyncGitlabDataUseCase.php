<?php

namespace App\Application\Gitlab;

use App\Application\UseCaseCollection;
use App\Application\UseCaseInterface;

final readonly class SyncGitlabDataUseCase implements UseCaseInterface
{
    public function __construct(
        private UseCaseCollection $useCaseCollection,
    ) {
    }

    public function execute(): void
    {
        foreach ($this->useCaseCollection as $useCase) {
            $useCase->execute();
        }
    }
}
