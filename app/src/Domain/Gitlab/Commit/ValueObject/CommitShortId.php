<?php

namespace App\Domain\Gitlab\Commit\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;

final readonly class CommitShortId extends AbstractRequiredString
{
}