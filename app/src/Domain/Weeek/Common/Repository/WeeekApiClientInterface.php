<?php

declare(strict_types=1);

namespace App\Domain\Weeek\Common\Repository;

interface WeeekApiClientInterface
{
    public function getWorkspaceMembers(): array;
    public function getWorkspaceTags(array $params = []): array;
    public function getWorkspaceTasks(array $params = []): array;
}
