<?php

namespace App\Domain\GitLab\Common;

use Psr\Http\Message\ResponseInterface;

interface GitLabApiClientInterface
{
    public function get(string $uri): ResponseInterface;
}
