<?php

declare(strict_types=1);

namespace App\Domain\Git\Stats\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class StatsDeletions extends AbstractRequiredUnsignedInt
{
}
