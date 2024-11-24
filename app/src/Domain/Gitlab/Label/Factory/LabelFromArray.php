<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label\Factory;

use App\Domain\Gitlab\Label\ProjectLabel;
use App\Domain\Gitlab\Label\ValueObject\LabelColor;
use App\Domain\Gitlab\Label\ValueObject\LabelId;
use App\Domain\Gitlab\Label\ValueObject\LabelName;
use App\Domain\Gitlab\Label\ValueObject\LabelProjectId;

class LabelFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): ProjectLabel
    {
        $projectId = $this->data['project_id'];
        return new ProjectLabel(
            new LabelId($this->data['id']),
            new LabelName($this->data['name']),
            new LabelColor($this->data['color']),
            new LabelProjectId($this->data['project_id']),
        );
    }
}
