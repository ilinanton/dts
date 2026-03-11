<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

interface GitlabApiClientUserInterface
{
    public function getGroupMembers(array $params = []): array;
}
