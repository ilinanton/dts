<?php

namespace App\Domain\Git\Commit;

final readonly class CommitFactory
{
    public function create(int $projectId, array $data): Commit
    {
        return new Commit(

        );
    }
}
