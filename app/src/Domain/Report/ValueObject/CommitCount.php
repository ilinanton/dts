<?php

declare(strict_types=1);

namespace App\Domain\Report\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class CommitCount extends AbstractRequiredUnsignedInt
{
}
