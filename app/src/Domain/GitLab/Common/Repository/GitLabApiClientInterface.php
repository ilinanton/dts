<?php

namespace App\Domain\GitLab\Common\Repository;

use Psr\Http\Message\ResponseInterface;

interface GitLabApiClientInterface
{
    public function get(string $uri, array $params = []): ResponseInterface;
    public function getGroupMembers(array $params = []): ResponseInterface;
    public function getGroupProjects(array $params = []): ResponseInterface;
}
