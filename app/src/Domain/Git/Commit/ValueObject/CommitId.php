<?php

declare(strict_types=1);

namespace App\Domain\Git\Commit\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class CommitId extends AbstractRequiredString
{
}
