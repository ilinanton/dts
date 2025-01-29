<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label;

use App\Domain\Gitlab\Label\ValueObject\LabelColor;
use App\Domain\Gitlab\Label\ValueObject\LabelId;
use App\Domain\Gitlab\Label\ValueObject\LabelName;

final readonly class LabelFactory
{
    public function create(array $data): Label
    {
        return new Label(
            new LabelId($data['id']),
            new LabelName($data['name']),
            new LabelColor($data['color']),
        );
    }
}
