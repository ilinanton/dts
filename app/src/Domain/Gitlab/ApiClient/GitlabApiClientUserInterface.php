<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ApiClient;

interface GitlabApiClientUserInterface
{
    public function getGroupMembers(array $params = []): array;
}
