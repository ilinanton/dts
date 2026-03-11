<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

interface GitlabApiClientLabelInterface
{
    public function getLabels(array $params = []): array;
}
