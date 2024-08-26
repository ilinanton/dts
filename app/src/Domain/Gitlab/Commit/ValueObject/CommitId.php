<?php

namespace App\Domain\Gitlab\Commit\ValueObject;

use App\Domain\Gitlab\Common\ValueObject\AbstractRequiredString;

final readonly class CommitId extends AbstractRequiredString
{
}
