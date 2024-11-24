<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label;

use App\Domain\Gitlab\Label\ValueObject\LabelColor;
use App\Domain\Gitlab\Label\ValueObject\LabelId;
use App\Domain\Gitlab\Label\ValueObject\LabelName;
use App\Domain\Gitlab\Label\ValueObject\LabelProjectId;

final readonly class ProjectLabel
{
    public function __construct(
        public LabelId $id,
        public LabelName $name,
        public LabelColor $color,
        public LabelProjectId $projectId,
    ) {
    }
}
