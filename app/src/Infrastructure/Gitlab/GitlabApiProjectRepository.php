<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Source\GitlabSourceProjectInterface;
use App\Infrastructure\Gitlab\Factory\ProjectFactory;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\Repository\GitlabSourceProjectRepositoryInterface;

final readonly class GitlabApiProjectRepository implements GitlabSourceProjectRepositoryInterface
{
    public function __construct(
        private GitlabSourceProjectInterface $client,
        private ProjectFactory $projectFactory,
        private array $excludedProjectIds,
    ) {
    }

    public function get(array $params = []): ProjectCollection
    {
        $data = $this->client->getGroupProjects($params);
        $data = array_filter($data, function (array $value): bool {
            return !in_array($value['id'], $this->excludedProjectIds);
        });

        return $this->projectFactory->createCollection($data);
    }
}
