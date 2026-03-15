<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab\Factory;

use App\Domain\Gitlab\Project\Project;
use App\Domain\Gitlab\Project\ProjectCollection;
use App\Domain\Gitlab\Project\ValueObject\ProjectDefaultBranch;
use App\Domain\Gitlab\Project\ValueObject\ProjectHttpUrlToRepoRequired;
use App\Domain\Gitlab\Project\ValueObject\ProjectId;
use App\Domain\Gitlab\Project\ValueObject\ProjectName;
use App\Domain\Gitlab\Project\ValueObject\ProjectRequiredWebUrl;
use App\Domain\Gitlab\Project\ValueObject\ProjectSshUrlToRepo;

final readonly class ProjectFactory
{
    /**
     * @param array{
     *     id?: int,
     *     name?: string,
     *     default_branch?: string,
     *     ssh_url_to_repo?: string,
     *     http_url_to_repo?: string,
     *     web_url?: string,
     * } $data
     */
    public function create(array $data): Project
    {
        return new Project(
            new ProjectId($data['id'] ?? 0),
            new ProjectName($data['name'] ?? ''),
            new ProjectDefaultBranch($data['default_branch'] ?? ''),
            new ProjectSshUrlToRepo($data['ssh_url_to_repo'] ?? ''),
            new ProjectHttpUrlToRepoRequired($data['http_url_to_repo'] ?? ''),
            new ProjectRequiredWebUrl($data['web_url'] ?? ''),
        );
    }

    /** @param array<int, array<string, mixed>> $data */
    public function createCollection(array $data): ProjectCollection
    {
        $collection = new ProjectCollection();
        array_walk(
            $data,
            function (array $item) use ($collection): void {
                $collection->add($this->create($item));
            },
        );

        return $collection;
    }
}
