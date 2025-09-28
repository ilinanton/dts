<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Common\Repository;

interface WeeekApiClientInterface
{
    public function getWorkspaceMembers(array $params = []): array;
}
