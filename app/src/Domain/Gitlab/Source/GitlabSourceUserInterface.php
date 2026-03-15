<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Source;

interface GitlabSourceUserInterface
{
    public function getGroupMembers(array $params = []): array;
}
