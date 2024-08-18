<?php

namespace App\Domain\GitLab\Commit\ValueObject;

use App\Domain\GitLab\Common\ValueObject\AbstractRequiredString;

final readonly class CommitShortId extends AbstractRequiredString
{
}
