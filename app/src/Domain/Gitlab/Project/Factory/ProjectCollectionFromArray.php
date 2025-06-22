<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Project\Factory;

use App\Domain\Gitlab\Project\ProjectCollection;

final class ProjectCollectionFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): ProjectCollection
    {
        $projectCollection = new ProjectCollection();
        array_walk(
            $this->data,
            function (array &$item) use ($projectCollection): void {
                $projectFactory = new ProjectFromArray($item);
                $projectCollection->add($projectFactory->create());
            },
        );
        return $projectCollection;
    }
}
