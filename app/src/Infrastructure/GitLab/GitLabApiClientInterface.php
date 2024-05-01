<?php

namespace App\Infrastructure\GitLab;

use Psr\Http\Message\ResponseInterface;

interface GitLabApiClientInterface
{
    public function get(string $uri): ResponseInterface;
}
