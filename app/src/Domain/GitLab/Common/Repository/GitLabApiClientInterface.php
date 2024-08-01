<?php

namespace App\Domain\GitLab\Common\Repository;

use Psr\Http\Message\ResponseInterface;

interface GitLabApiClientInterface
{
    public function get(string $uri, array $params = []): array;
    public function getGroupMembers(array $params = []): array;
    public function getGroupProjects(array $params = []): array;
    public function getGroupMergeRequests(array $params = []): array;
}
