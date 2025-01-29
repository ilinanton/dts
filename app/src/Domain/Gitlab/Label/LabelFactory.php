<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label;

use App\Domain\Gitlab\Label\ValueObject\LabelColor;
use App\Domain\Gitlab\Label\ValueObject\LabelId;
use App\Domain\Gitlab\Label\ValueObject\LabelName;

class LabelFromArray
{
    public function __construct(
        private array $data,
    ) {
    }

    public function create(): Label
    {
        return new Label(
            new LabelId($this->data['id']),
            new LabelName($this->data['name']),
            new LabelColor($this->data['color']),
        );
    }
}
