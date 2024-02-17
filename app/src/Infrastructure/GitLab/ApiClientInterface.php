<?php

namespace App\Infrastructure\GitLab;

use Psr\Http\Message\ResponseInterface;

interface ApiClientInterface
{
    public function get(string $uri): ResponseInterface;
}
