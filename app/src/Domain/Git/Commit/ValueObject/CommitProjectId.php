<?php

namespace App\Domain\Git\Commit\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class CommitProjectId extends AbstractRequiredUnsignedInt
{
}
