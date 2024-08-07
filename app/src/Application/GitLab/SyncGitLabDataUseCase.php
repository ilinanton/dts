<?php

namespace App\Application\GitLab;

use App\Application\UseCaseCollection;
use App\Application\UseCaseInterface;

final readonly class SyncGitLabDataUseCase implements UseCaseInterface
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
