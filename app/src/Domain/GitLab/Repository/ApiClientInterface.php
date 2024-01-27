<?php

namespace App\Domain\GitLab\Repository;

use Psr\Http\Message\ResponseInterface;

interface ApiClientInterface
{
    public function get(string $uri): ResponseInterface;
}
