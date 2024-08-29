<?php

namespace App\Domain\Gitlab\CommitStats\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class CommitStatsProjectId extends AbstractRequiredUnsignedInt
{
}
