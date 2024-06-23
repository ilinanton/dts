<?php

namespace App\Domain\GitLab\Common\Repository;

use Psr\Http\Message\ResponseInterface;

interface GitLabApiClientInterface
{
    public function get(string $uri): ResponseInterface;
}
