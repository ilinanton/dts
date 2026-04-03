<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Commit\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredUnsignedInt;

final readonly class CommitProjectId extends AbstractRequiredUnsignedInt
{
}
