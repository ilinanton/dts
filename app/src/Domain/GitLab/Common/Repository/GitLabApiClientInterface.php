<?php

namespace App\Domain\GitLab\Common\Repository;

interface GitLabApiClientInterface
{
    public function get(string $uri, array $params = []): array;

    public function getGroupMembers(array $params = []): array;

    public function getGroupProjects(array $params = []): array;

    public function getGroupMergeRequests(array $params = []): array;

    public function getProjectMergeRequests(int $projectId, array $params = []): array;

    public function getProjectEvents(int $projectId, array $params = []): array;

    public function getUserEvents(int $userId, array $params = []): array;
}
