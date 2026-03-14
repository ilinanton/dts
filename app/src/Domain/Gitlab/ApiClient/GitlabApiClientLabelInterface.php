<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\ApiClient;

interface GitlabApiClientLabelInterface
{
    public function getLabels(array $params = []): array;
}
