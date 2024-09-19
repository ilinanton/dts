<?php

namespace App\Domain\Gitlab\Commit\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class CommitProjectId extends AbstractRequiredUnsignedInt
{
}
