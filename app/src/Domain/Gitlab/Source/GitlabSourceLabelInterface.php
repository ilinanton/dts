<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Source;

interface GitlabSourceLabelInterface
{
    public function getLabels(array $params = []): array;
}
