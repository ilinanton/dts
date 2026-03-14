<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Project\Factory;

use App\Domain\Gitlab\Project\ProjectCollection;

final readonly class ProjectCollectionFromArray
{
    public function create(array $data): ProjectCollection
    {
        $projectCollection = new ProjectCollection();
        $projectFactory = new ProjectFromArray();
        array_walk(
            $data,
            function (array $item) use ($projectCollection, $projectFactory): void {
                $projectCollection->add($projectFactory->create($item));
            },
        );
        return $projectCollection;
    }
}
