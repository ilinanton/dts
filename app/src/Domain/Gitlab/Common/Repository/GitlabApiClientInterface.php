<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Common\Repository;

interface GitlabApiClientInterface
{
    public function get(string $uri, array $params = []): array;

    public function getGroupMembers(array $params = []): array;

    public function getGroupProjects(array $params = []): array;

    public function getGroupMergeRequests(array $params = []): array;

    public function getProjectMergeRequests(int $projectId, array $params = []): array;

    public function getProjectEvents(int $projectId, array $params = []): array;

    public function getProjectRepositoryCommits(int $projectId, array $params = []): array;

    public function getUserEvents(int $userId, array $params = []): array;
}
