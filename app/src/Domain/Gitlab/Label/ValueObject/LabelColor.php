<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Label\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class LabelColor extends AbstractRequiredString
{
}
